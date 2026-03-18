<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\SmsMessage;
use Illuminate\Http\Request;

class SmsInboxController extends Controller
{
    public function index(Request $request)
    {
        $farmers = Farmer::where('wants_sms', true)
            ->orderBy('full_name')
            ->get();

        $selectedFarmerId = $request->query('farmer_id');

        if (!$selectedFarmerId && $farmers->isNotEmpty()) {
            $selectedFarmerId = $farmers->first()->id;
        }

        $latestMessage = null;
        $selectedFarmer = null;

        if ($selectedFarmerId) {
            $selectedFarmer = Farmer::find($selectedFarmerId);

            $latestMessage = SmsMessage::where('farmer_id', $selectedFarmerId)
                ->latest('sent_at')
                ->latest('id')
                ->first();
        }

        return view('sms-inbox', compact(
            'farmers',
            'selectedFarmer',
            'latestMessage'
        ));
    }

    public function latest(Request $request)
    {
        $farmerId = $request->query('farmer_id');

        if (!$farmerId) {
            return response()->json([
                'found' => false,
            ]);
        }

        $message = SmsMessage::where('farmer_id', $farmerId)
            ->latest('sent_at')
            ->latest('id')
            ->first();

        if (!$message) {
            return response()->json([
                'found' => false,
            ]);
        }

        return response()->json([
            'found' => true,
            'id' => $message->id,
            'farmer_name' => $message->farmer_name,
            'phone_number' => $message->phone_number,
            'language' => $message->language,
            'message' => $message->message,
            'sent_at' => optional($message->sent_at)->format('H:i'),
        ]);
    }
}
