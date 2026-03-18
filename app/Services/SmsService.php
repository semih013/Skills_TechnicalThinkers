<?php

namespace App\Services;

use Throwable;
use Twilio\Rest\Client;

class SmsService
{
    private ?string $sid;
    private ?string $token;
    private ?string $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.account_sid');
        $this->token = config('services.twilio.auth_token');
        $this->from = config('services.twilio.from');
    }

    public function send(string $to, string $message): bool
    {
        if (empty($this->sid) || empty($this->token) || empty($this->from)) {
            return false;
        }

        try {
            $client = new Client($this->sid, $this->token);

            $client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            return true;
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }
}
