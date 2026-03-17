<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Farmer;
use App\Services\SmsService;
use App\Services\TranslationService;
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
     * Preview alert before sending
     */
    public function preview(Request $request, TranslationService $translationService)
    {
        $validated = $request->validate([
            'send_mode' => 'required|in:region,individual',
            'region' => 'nullable|string',
            'farmer_id' => 'nullable|exists:farmers,id',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        if ($validated['send_mode'] === 'individual') {
            $recipients = Farmer::where('id', $validated['farmer_id'])
                ->where('wants_sms', true)
                ->get();
        } else {
            $recipients = Farmer::where('region', $validated['region'])
                ->where('wants_sms', true)
                ->get();
        }

        $recipients = $recipients->map(function ($farmer) use ($validated, $translationService) {
            $farmer->translated_message = $translationService->translate(
                $validated['message'],
                $farmer->preferred_language
            );

            return $farmer;
        });

        $selectedRegion = null;
        $selectedFarmer = null;

        if ($validated['send_mode'] === 'individual' && ! empty($validated['farmer_id'])) {
            $selectedFarmer = Farmer::find($validated['farmer_id']);
            $selectedRegion = $selectedFarmer?->region;
        } else {
            $selectedRegion = $validated['region'];
        }

        return view('alerts.preview', [
            'alertData' => $validated,
            'recipients' => $recipients,
            'selectedRegion' => $selectedRegion,
            'selectedFarmer' => $selectedFarmer,
        ]);
    }

    public function send(Request $request, SmsService $smsService, TranslationService $translationService)
    {
        $validated = $request->validate([
            'send_mode' => 'required|in:region,individual',
            'region' => 'nullable|string',
            'farmer_id' => 'nullable|exists:farmers,id',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
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

        foreach ($recipients as $farmer) {
            $translatedMessage = $translationService->translate($validated['message'], $farmer->preferred_language);
            $smsService->send($farmer->phone_number, $translatedMessage);
        }

        Alert::create([
            'region' => $targetRegion,
            'alert_type' => $validated['alert_type'],
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Alert sent successfully to '.$recipients->count().' farmers.');
    }
}
