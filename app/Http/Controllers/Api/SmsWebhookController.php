<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendSmsRequest;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsWebhookController extends Controller
{
    public function __construct(protected SmsService $smsService) {}

    /**
     * Handle the incoming request to send an SMS message.
     */
    public function send(SendSmsRequest $request): JsonResponse
    {
        $message = $this->smsService->send(
            $request->input('phone_number'),
            $request->input('message')
        );

        return response()->json([
            'message' => 'SMS message sent successfully.',
            'data' => $message,
        ], 201);
    }
}
