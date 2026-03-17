<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SmsTestController extends Controller
{
    public function index(): View
    {
        return view('sms.test');
    }

    public function send(Request $request, SmsService $smsService): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'message' => 'required|string|max:160',
        ]);

        $success = $smsService->send($validated['phone_number'], $validated['message']);

        if (! $success) {
            return redirect()
                ->route('sms.test')
                ->with('error', 'Failed to send SMS. Check your Twilio settings and logs.')
                ->withInput();
        }

        return redirect()
            ->route('sms.test')
            ->with('success', 'SMS sent to '.$validated['phone_number'].'.')
            ->withInput();
    }
}
