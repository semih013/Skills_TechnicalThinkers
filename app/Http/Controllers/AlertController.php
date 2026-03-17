<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Farmer;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Show all alerts (Alerts page)
     */
    public function index()
    {
        $alerts = Alert::latest()->get();

        return view('alerts.index', compact('alerts'));
    }

    /**
     * Show create alert form
     */
    public function create()
    {
        $regions = Farmer::select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $farmers = Farmer::where('wants_sms', true)
            ->orderBy('full_name')
            ->get();

        return view('alerts.create', compact('regions', 'farmers'));
    }

    /**
     * Translate message based on farmer language
     */
    private function translateMessage($message, $language)
    {
        if ($language === 'Swahili') {
            return match (strtolower(trim($message))) {
                'cloudy' => 'Kuna mawingu',
                'rain expected tomorrow. delay planting by 2 days.' => 'Mvua inatarajiwa kesho. Chelewesha kupanda kwa siku 2.',
                'fall armyworm detected nearby. check maize crops today.' => 'Funza jeshi wameonekana karibu. Angalia mazao ya mahindi leo.',
                'low rainfall expected this week. consider irrigation if possible.' => 'Mvua ndogo inatarajiwa wiki hii. Fikiria umwagiliaji ikiwezekana.',
                default => '[Swahili] ' . $message,
            };
        }

        return $message;
    }

    /**
     * Preview alert before sending
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $recipients = Farmer::where('region', $validated['region'])
            ->where('wants_sms', true)
            ->get()
            ->map(function ($farmer) use ($validated) {
                $farmer->translated_message = $this->translateMessage(
                    $validated['message'],
                    $farmer->preferred_language
                );

                return $farmer;
            });

        return view('alerts.preview', [
            'alertData' => $validated,
            'recipients' => $recipients,
        ]);
    }

    /**
     * Simulate sending alert
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $recipients = Farmer::where('region', $validated['region'])
            ->where('wants_sms', true)
            ->get();

        Alert::create([
            'region' => $validated['region'],
            'alert_type' => $validated['alert_type'],
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Alert sent successfully to ' . $recipients->count() . ' farmers.');
    }
}
