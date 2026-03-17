<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS message.
     */
    public function send(string $phoneNumber, string $content): Message
    {
        // In a real application, you would call an SMS gateway API here (e.g., Twilio, Africa's Talking)
        // For now, we will just log the message and store it in the database.

        Log::info("Sending SMS to {$phoneNumber}: {$content}");

        return Message::create([
            'phone_number' => $phoneNumber,
            'content' => $content,
            'recipient_group' => 'Direct SMS',
            'sent_at' => now(),
            'status' => 'Sent',
        ]);
    }

    /**
     * Send an SMS message to all farmers.
     */
    public function sendToAll(string $content): Message
    {
        Log::info("Sending SMS to ALL farmers: {$content}");

        return Message::create([
            'content' => $content,
            'recipient_group' => 'All Farmers',
            'sent_at' => now(),
            'status' => 'Sent',
        ]);
    }
}
