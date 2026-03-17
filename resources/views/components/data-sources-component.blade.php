<?php

use Livewire\Component;
use App\Models\DataSource;

new class extends Component
{
    public function with(): array
    {
        return [
            'dataSources' => DataSource::all(),
        ];
    }
};
?>

<div class="space-y-6">
    <flux:heading size="xl">{{ __('Data Sources') }}</flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($dataSources as $source)
            <flux:card class="flex flex-col space-y-4">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg">{{ $source->name }}</flux:heading>
                    <flux:badge :color="$source->status === 'Active' ? 'green' : 'zinc'">
                        {{ $source->status }}
                    </flux:badge>
                </div>

                <div class="flex-1">
                    @if($source->name === 'Weather Data')
                        <flux:icon icon="cloud-sun" class="size-12 text-blue-500 mb-2" />
                        <flux:text>{{ __('Providing real-time weather forecasts and historical patterns.') }}</flux:text>
                    @elseif($source->name === 'Satellite Data')
                        <flux:icon icon="satellite" class="size-12 text-indigo-500 mb-2" />
                        <flux:text>{{ __('NDVI indices and crop health monitoring via satellite imagery.') }}</flux:text>
                    @elseif($source->name === 'Pest Detection')
                        <flux:icon icon="bug" class="size-12 text-red-500 mb-2" />
                        <flux:text>{{ __('Early warning system for migratory pests and local outbreaks.') }}</flux:text>
                    @elseif($source->name === 'Market Prices')
                        <flux:icon icon="trending-up" class="size-12 text-green-500 mb-2" />
                        <flux:text>{{ __('Daily commodity prices from major regional trade hubs.') }}</flux:text>
                    @endif
                </div>

                <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:text size="xs" class="text-zinc-500 uppercase tracking-wider font-semibold">
                        {{ __('Last Updated') }}
                    </flux:text>
                    <flux:text size="sm">
                        {{ $source->last_updated_at?->diffForHumans() ?? __('Never') }}
                    </flux:text>
                </div>
            </flux:card>
        @endforeach
    </div>
</div>
