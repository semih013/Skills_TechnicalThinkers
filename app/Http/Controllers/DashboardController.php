<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Submission;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Submission::count();
        $totalAlerts = Alert::count();
        $highRiskCount = Submission::where('crop_condition', 'poor')->count();
        $pestReports = Submission::where('pest_detected', true)->count();

        $recentSubmissions = Submission::latest()->take(5)->get();
        $recentAlerts = Alert::latest()->take(5)->get();

        // Choose region
        $regionCoordinates = [
            'Arusha' => ['lat' => -3.3869, 'lon' => 36.6830],
            'Kilimanjaro' => ['lat' => -3.0674, 'lon' => 37.3556],
            'Dodoma' => ['lat' => -6.1630, 'lon' => 35.7516],
        ];

        $selectedRegion = request('region', 'Arusha');

        if (!array_key_exists($selectedRegion, $regionCoordinates)) {
            $selectedRegion = 'Arusha';
        }

        $forecast = [];

        if (isset($regionCoordinates[$selectedRegion])) {
            $lat = $regionCoordinates[$selectedRegion]['lat'];
            $lon = $regionCoordinates[$selectedRegion]['lon'];

            $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lon,
                'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                'timezone' => 'auto',
                'forecast_days' => 7,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['daily']['time'])) {
                    foreach ($data['daily']['time'] as $index => $date) {
                        $weatherCode = $data['daily']['weather_code'][$index] ?? null;

                        $forecast[] = [
                            'date' => $date,
                            'day' => \Carbon\Carbon::parse($date)->format('D'),
                            'weather_code' => $weatherCode,
                            'condition' => $this->getWeatherCondition($weatherCode),
                            'temp_max' => $data['daily']['temperature_2m_max'][$index] ?? null,
                            'temp_min' => $data['daily']['temperature_2m_min'][$index] ?? null,
                            'rain_chance' => $data['daily']['precipitation_probability_max'][$index] ?? null,
                        ];
                    }
                }
            }
        }

        return view('dashboard', compact(
            'totalReports',
            'totalAlerts',
            'highRiskCount',
            'pestReports',
            'recentSubmissions',
            'recentAlerts',
            'forecast',
            'selectedRegion',
            'regionCoordinates'
        ));
    }

    private function getWeatherCondition($code)
    {
        return match ($code) {
            0 => 'Clear',
            1, 2, 3 => 'Cloudy',
            45, 48 => 'Fog',
            51, 53, 55, 56, 57 => 'Drizzle',
            61, 63, 65, 66, 67 => 'Rain',
            71, 73, 75, 77 => 'Snow',
            80, 81, 82 => 'Rain Showers',
            85, 86 => 'Snow Showers',
            95, 96, 99 => 'Thunderstorm',
            default => 'Unknown',
        };
    }
}
