<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('webhook can send an SMS message', function () {
    $phoneNumber = '+255712345678';
    $messageText = 'Hello Musa, your crop alert is here!';

    $response = $this->postJson('/api/webhooks/send-sms', [
        'phone_number' => $phoneNumber,
        'message' => $messageText,
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'SMS message sent successfully.',
        ]);

    $this->assertDatabaseHas('messages', [
        'phone_number' => $phoneNumber,
        'content' => $messageText,
        'status' => 'Sent',
    ]);
});

test('webhook validates required fields', function () {
    $response = $this->postJson('/api/webhooks/send-sms', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['phone_number', 'message']);
});

test('webhook validates message length', function () {
    $response = $this->postJson('/api/webhooks/send-sms', [
        'phone_number' => '+255712345678',
        'message' => str_repeat('a', 161),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});
