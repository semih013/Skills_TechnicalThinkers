<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Farmer;
use App\Services\SmsService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function create()
    {
        $regions = Farmer::select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('alerts.create', compact('regions'));
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $recipients = Farmer::where('region', $validated['region'])
            ->where('wants_sms', true)
            ->get();

        return view('alerts.preview', [
            'alertData' => $validated,
            'recipients' => $recipients,
        ]);
    }

    public function send(Request $request, SmsService $smsService)
    {
        $validated = $request->validate([
            'region' => 'required|string',
            'alert_type' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $recipients = Farmer::where('region', $validated['region'])
            ->where('wants_sms', true)
            ->get();

        foreach ($recipients as $farmer) {
            $smsService->send($farmer->phone_number, $validated['message']);
        }

        Alert::create([
            'region' => $validated['region'],
            'alert_type' => $validated['alert_type'],
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Alert sent successfully to '.$recipients->count().' farmers.');
    }
}
