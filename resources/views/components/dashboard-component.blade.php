<?php

use Livewire\Component;
use App\Models\Farmer;
use App\Models\Alert;
use App\Models\Message;

new class extends Component
{
    public function with(): array
    {
        return [
            'totalFarmers' => Farmer::count(),
            'activeAlerts' => Alert::where('status', 'Scheduled')->count(),
            'messagesToday' => Message::whereDate('sent_at', now())->count(),
            'recentAlerts' => Alert::latest()->take(5)->get(),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <flux:card class="flex flex-col items-center justify-center p-6">
            <flux:text class="text-lg font-medium">{{ __('Total Farmers') }}</flux:text>
            <flux:heading size="xl">{{ $totalFarmers }}</flux:heading>
        </flux:card>

        <flux:card class="flex flex-col items-center justify-center p-6">
            <flux:text class="text-lg font-medium">{{ __('Active Alerts') }}</flux:text>
            <flux:heading size="xl">{{ $activeAlerts }}</flux:heading>
        </flux:card>

        <flux:card class="flex flex-col items-center justify-center p-6">
            <flux:text class="text-lg font-medium">{{ __('Messages Sent Today') }}</flux:text>
            <flux:heading size="xl">{{ $messagesToday }}</flux:heading>
        </flux:card>
    </div>

    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="lg">{{ __('Recent Alerts') }}</flux:heading>
            <flux:button variant="subtle" href="{{ route('alerts') }}" wire:navigate>{{ __('View All') }}</flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Message') }}</flux:table.column>
                <flux:table.column>{{ __('Region') }}</flux:table.column>
                <flux:table.column>{{ __('Crop') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($recentAlerts as $alert)
                    <flux:table.row>
                        <flux:table.cell class="max-w-xs truncate">{{ $alert->message }}</flux:table.cell>
                        <flux:table.cell>{{ $alert->region }}</flux:table.cell>
                        <flux:table.cell>{{ $alert->crop_type }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$alert->status === 'Sent' ? 'green' : 'yellow'" inset="top bottom">
                                {{ $alert->status }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
