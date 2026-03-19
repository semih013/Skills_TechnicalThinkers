<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class SmsRegistrationDemoController extends Controller
{
    public function index()
    {
        return view('sms-registration-demo');
    }

    public function store(Request $request)
    {
        $phone = trim($request->input('from'));
        $message = trim($request->input('text'));

        if (!$phone || !$message) {
            return back()->withInput()->with('error', 'Phone number or message is missing.');
        }

        if (stripos($message, 'REG ') !== 0) {
            return back()->withInput()->with('error', 'Invalid format. Use: REG Full Name, Region, Village, Language');
        }

        $dataPart = trim(substr($message, 4));
        $parts = array_map('trim', explode(',', $dataPart));

        if (count($parts) !== 4) {
            return back()->withInput()->with('error', 'Invalid format. Use: REG Full Name, Region, Village, Language');
        }

        [$fullName, $region, $village, $language] = $parts;

        if (Farmer::where('phone_number', $phone)->exists()) {
            return back()->withInput()->with('error', 'This phone number is already registered.');
        }

        Farmer::create([
            'full_name' => $fullName,
            'phone_number' => $phone,
            'region' => $region,
            'village' => $village,
            'preferred_language' => $language,
            'wants_sms' => true,
        ]);

        return back()
            ->withInput()
            ->with('success', "Farmer registered successfully: {$fullName}")
            ->with('reply_message', "Welcome {$fullName}, registration successful.");
    }
}
