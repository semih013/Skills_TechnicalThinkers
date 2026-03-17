<?php

use App\Models\Farmer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('farmers page is accessible', function () {
    $response = $this->get('/farmers');

    $response->assertStatus(200);
});

test('can send message to a specific farmer', function () {
    $farmer = Farmer::factory()->create(['name' => 'Musa Hassan', 'phone_number' => '+255712345678']);

    Livewire::test('farmers-component')
        ->call('openMessageModal', $farmer->id)
        ->set('message_content', 'Hello Musa, your alert is here!')
        ->call('sendMessage')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('messages', [
        'phone_number' => '+255712345678',
        'content' => 'Hello Musa, your alert is here!',
        'recipient_group' => 'Direct SMS',
        'status' => 'Sent',
    ]);
});

test('can send message to all farmers', function () {
    Farmer::factory(5)->create();

    Livewire::test('farmers-component')
        ->call('openMessageModal', null)
        ->set('message_content', 'General update for all farmers.')
        ->call('sendMessage')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('messages', [
        'content' => 'General update for all farmers.',
        'recipient_group' => 'All Farmers',
        'status' => 'Sent',
    ]);
});

test('message content is required', function () {
    $farmer = Farmer::factory()->create();

    Livewire::test('farmers-component')
        ->call('openMessageModal', $farmer->id)
        ->set('message_content', '')
        ->call('sendMessage')
        ->assertHasErrors(['message_content' => 'required']);
});
