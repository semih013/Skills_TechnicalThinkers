<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SmsMessage;
use App\Services\SmsService;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::latest()->get();

        return view('alerts.index', compact('alerts'));
    }

    public function create()
    {
        $regions = Farmer::select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $farmers = Farmer::where('wants_sms', true)
            ->orderBy('full_name')
            ->get();

        $selectedRegion = request('region', 'Arusha');
        $suggestedAlert = $this->generateSuggestedAlert($selectedRegion);

        return view('alerts.create', compact('regions', 'farmers', 'selectedRegion', 'suggestedAlert'));
    }

    private function translateMessage($message, $language)
    {
        if ($language !== 'Swahili' || empty($message)) {
            return $message;
        }

        $translated = trim($message);

        $fullTranslations = [
            'Rain showers are expected today. Farmers are advised to delay planting and protect seedlings.'
            => 'Mvua za vipindi zinatarajiwa leo. Wakulima wanashauriwa kuchelewesha kupanda na kulinda miche.',

            'Rain is expected today. Farmers are advised to delay planting and protect seedlings.'
            => 'Mvua inatarajiwa leo. Wakulima wanashauriwa kuchelewesha kupanda na kulinda miche.',

            'Thunderstorms are expected today. Farmers are advised to protect seedlings and avoid spraying.'
            => 'Mvua za radi zinatarajiwa leo. Wakulima wanashauriwa kulinda miche na kuepuka kunyunyiza dawa.',

            'Clear weather is expected today. Conditions are good for field activities.'
            => 'Hali ya hewa safi inatarajiwa leo. Mazingira ni mazuri kwa shughuli za shambani.',

            'Cloudy weather is expected today. Monitor crops and prepare for possible rain.'
            => 'Hali ya mawingu inatarajiwa leo. Fuatilia mazao na jiandae kwa mvua inayowezekana.',

            'Market price for maize is decreasing. Consider selling early or storing safely.'
            => 'Bei ya mahindi inashuka. Fikiria kuuza mapema au kuhifadhi kwa usalama.',
        ];

        if (isset($fullTranslations[$translated])) {
            return $fullTranslations[$translated];
        }

        $translated = str_replace(
            'Rain showers are expected today.',
            'Mvua za vipindi zinatarajiwa leo.',
            $translated
        );

        $translated = str_replace(
            'Rain is expected today.',
            'Mvua inatarajiwa leo.',
            $translated
        );

        $translated = str_replace(
            'Thunderstorms are expected today.',
            'Mvua za radi zinatarajiwa leo.',
            $translated
        );

        $translated = str_replace(
            'Clear weather is expected today.',
            'Hali ya hewa safi inatarajiwa leo.',
            $translated
        );

        $translated = str_replace(
            'Cloudy weather is expected today.',
            'Hali ya mawingu inatarajiwa leo.',
            $translated
        );

        $translated = str_replace(
            'Farmers are advised to delay planting and protect seedlings.',
            'Wakulima wanashauriwa kuchelewesha kupanda na kulinda miche.',
            $translated
        );

        $translated = str_replace(
            'Farmers are advised to protect seedlings and avoid spraying.',
            'Wakulima wanashauriwa kulinda miche na kuepuka kunyunyiza dawa.',
            $translated
        );

        $translated = str_replace(
            'Conditions are good for field activities.',
            'Mazingira ni mazuri kwa shughuli za shambani.',
            $translated
        );

        $translated = str_replace(
            'Monitor crops and prepare for possible rain.',
            'Fuatilia mazao na jiandae kwa mvua inayowezekana.',
            $translated
        );

        $translated = str_replace(
            'Market price for maize is decreasing.',
            'Bei ya mahindi inashuka.',
            $translated
        );

        $translated = str_replace(
            'Consider selling early or storing safely.',
            'Fikiria kuuza mapema au kuhifadhi kwa usalama.',
            $translated
        );

        return $translated;
    }

    private function translateWeatherSummary($summary, $language)
    {
        if ($language !== 'Swahili' || empty($summary)) {
            return $summary;
        }

        $translated = $summary;

        $translated = str_replace('Forecast:', 'Utabiri:', $translated);
        $translated = str_replace('Rain Showers', 'Mvua za vipindi', $translated);
        $translated = str_replace('Thunderstorm', 'Mvua za radi', $translated);
        $translated = str_replace('Cloudy', 'Kuna mawingu', $translated);
        $translated = str_replace('Clear', 'Kuna jua', $translated);
        $translated = str_replace('Drizzle', 'Mvua nyepesi', $translated);
        $translated = str_replace('Fog', 'Ukungu', $translated);
        $translated = str_replace('Rain', 'Mvua', $translated);
        $translated = str_replace('% rain', '% uwezekano wa mvua', $translated);

        return $translated;
    }

    private function getWeatherCondition($code)
    {
        return match ($code) {
            0 => 'Clear',
            1, 2, 3 => 'Cloudy',
            45, 48 => 'Fog',
            51, 53, 55 => 'Drizzle',
            61, 63, 65 => 'Rain',
            71, 73, 75 => 'Snow',
            80, 81, 82 => 'Rain Showers',
            95 => 'Thunderstorm',
            default => 'Unknown',
        };
    }

    private function buildWeatherSummary($region)
    {
        $regionCoordinates = [
            'Arusha' => ['lat' => -3.3869, 'lon' => 36.6830],
            'Kilimanjaro' => ['lat' => -3.0674, 'lon' => 37.3556],
            'Dodoma' => ['lat' => -6.1630, 'lon' => 35.7516],
        ];

        if (!isset($regionCoordinates[$region])) {
            return null;
        }

        $lat = $regionCoordinates[$region]['lat'];
        $lon = $regionCoordinates[$region]['lon'];

        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
            'timezone' => 'auto',
            'forecast_days' => 1,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (!isset($data['daily']['weather_code'][0])) {
            return null;
        }

        $condition = $this->getWeatherCondition($data['daily']['weather_code'][0]);
        $tempMax = $data['daily']['temperature_2m_max'][0] ?? null;
        $tempMin = $data['daily']['temperature_2m_min'][0] ?? null;
        $rainChance = $data['daily']['precipitation_probability_max'][0] ?? null;

        return "Forecast: {$condition}, {$rainChance}% rain, {$tempMax}°/{$tempMin}°.";
    }

    private function generateSuggestedAlert($region)
    {
        $regionCoordinates = [
            'Arusha' => ['lat' => -3.3869, 'lon' => 36.6830],
            'Kilimanjaro' => ['lat' => -3.0674, 'lon' => 37.3556],
            'Dodoma' => ['lat' => -6.1630, 'lon' => 35.7516],
        ];

        if (!isset($regionCoordinates[$region])) {
            return null;
        }

        $lat = $regionCoordinates[$region]['lat'];
        $lon = $regionCoordinates[$region]['lon'];

        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'daily' => 'weather_code,precipitation_probability_max',
            'timezone' => 'auto',
            'forecast_days' => 1,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (!isset($data['daily']['weather_code'][0])) {
            return null;
        }

        $condition = $this->getWeatherCondition($data['daily']['weather_code'][0]);
        $rainChance = $data['daily']['precipitation_probability_max'][0] ?? 0;

        return match ($condition) {
            'Rain' => 'Rain is expected today. Farmers are advised to delay planting and protect seedlings.',
            'Rain Showers' => 'Rain showers are expected today. Farmers are advised to delay planting and protect seedlings.',
            'Thunderstorm' => 'Thunderstorms are expected today. Farmers are advised to protect seedlings and avoid spraying.',
            'Clear' => 'Clear weather is expected today. Conditions are good for field activities.',
            'Cloudy' => 'Cloudy weather is expected today. Monitor crops and prepare for possible rain.',
            default => $rainChance >= 60
                ? 'Rain is likely today. Farmers should prepare their fields and protect young crops.'
                : 'Weather conditions should be monitored today before major field activities.',
        };
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'send_mode' => 'required|in:region,individual',
            'region' => 'nullable|string',
            'farmer_id' => 'nullable|exists:farmers,id',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:250',
        ]);

        if ($validated['send_mode'] === 'individual') {
            $recipients = Farmer::where('id', $validated['farmer_id'])
                ->where('wants_sms', true)
                ->get();

            $selectedFarmer = Farmer::find($validated['farmer_id']);
            $targetRegion = $selectedFarmer?->region;
        } else {
            $recipients = Farmer::where('region', $validated['region'])
                ->where('wants_sms', true)
                ->get();

            $selectedFarmer = null;
            $targetRegion = $validated['region'];
        }

        $weatherSummary = $this->buildWeatherSummary($targetRegion);

        $finalMessage = $validated['message'];
        if ($weatherSummary) {
            $finalMessage .= ' ' . $weatherSummary;
        }

        $recipients = $recipients->map(function ($farmer) use ($validated, $weatherSummary) {
            $baseTranslated = $this->translateMessage(
                $validated['message'],
                $farmer->preferred_language
            );

            $weatherTranslated = $weatherSummary
                ? $this->translateWeatherSummary($weatherSummary, $farmer->preferred_language)
                : '';

            $farmer->translated_message = trim($baseTranslated . ' ' . $weatherTranslated);

            return $farmer;
        });

        return view('alerts.preview', [
            'alertData' => $validated,
            'recipients' => $recipients,
            'selectedRegion' => $targetRegion,
            'selectedFarmer' => $selectedFarmer,
            'finalMessage' => $finalMessage,
            'weatherSummary' => $weatherSummary,
        ]);
    }

    public function send(Request $request, SmsService $smsService)
    {
        $validated = $request->validate([
            'send_mode' => 'required|in:region,individual',
            'region' => 'nullable|string',
            'farmer_id' => 'nullable|exists:farmers,id',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:350',
        ]);

        if ($validated['send_mode'] === 'individual') {
            $recipients = Farmer::where('id', $validated['farmer_id'])
                ->where('wants_sms', true)
                ->get();

            $targetRegion = optional($recipients->first())->region;
        } else {
            $recipients = Farmer::where('region', $validated['region'])
                ->where('wants_sms', true)
                ->get();

            $targetRegion = $validated['region'];
        }

        Alert::create([
            'region' => $targetRegion,
            'alert_type' => $validated['alert_type'],
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        foreach ($recipients as $recipient) {
            $translatedMessage = $validated['message'];

            if (str_contains($validated['message'], 'Forecast:')) {
                $parts = explode('Forecast:', $validated['message'], 2);

                $baseMessage = trim($parts[0]);
                $forecastPart = isset($parts[1]) ? 'Forecast:' . trim($parts[1]) : '';

                $baseTranslated = $this->translateMessage($baseMessage, $recipient->preferred_language);
                $forecastTranslated = $this->translateWeatherSummary($forecastPart, $recipient->preferred_language);

                $translatedMessage = trim($baseTranslated . ' ' . $forecastTranslated);
            } else {
                $translatedMessage = $this->translateMessage($validated['message'], $recipient->preferred_language);
            }

            SmsMessage::create([
                'farmer_id' => $recipient->id,
                'farmer_name' => $recipient->full_name,
                'phone_number' => $recipient->phone_number,
                'language' => $recipient->preferred_language,
                'message' => $translatedMessage,
                'sent_at' => now(),
            ]);

            $smsService->send($recipient->phone_number, $translatedMessage);
        }

        return redirect()
            ->route('sms.inbox')
            ->with('success', 'Alert sent successfully to ' . $recipients->count() . ' farmers.');
    }
}
