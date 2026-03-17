<?php

use Livewire\Component;
use App\Models\Alert;

new class extends Component
{
    public string $message = '';
    public string $region = '';
    public string $crop_type = '';
    public string $schedule_time = '';

    public function createAlert(): void
    {
        $validated = $this->validate([
            'message' => 'required|string|max:160',
            'region' => 'required|string',
            'crop_type' => 'required|string',
        ]);

        Alert::create([
            ...$validated,
            'status' => 'Scheduled',
        ]);

        $this->reset(['message', 'region', 'crop_type', 'schedule_time']);
        $this->modal('create-alert-modal')->close();
    }

    public function with(): array
    {
        return [
            'alerts' => Alert::latest()->paginate(10),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Alerts') }}</flux:heading>

        <flux:modal.trigger name="create-alert-modal">
            <flux:button variant="primary" icon="plus">{{ __('Create Alert') }}</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Message Preview') }}</flux:table.column>
                <flux:table.column>{{ __('Target Crop') }}</flux:table.column>
                <flux:table.column>{{ __('Region') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($alerts as $alert)
                    <flux:table.row>
                        <flux:table.cell class="max-w-md truncate">{{ $alert->message }}</flux:table.cell>
                        <flux:table.cell>{{ $alert->crop_type }}</flux:table.cell>
                        <flux:table.cell>{{ $alert->region }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$alert->status === 'Sent' ? 'green' : 'yellow'">
                                {{ $alert->status }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $alerts->links() }}
        </div>
    </flux:card>

    <flux:modal name="create-alert-modal" class="md:w-[500px]">
        <form wire:submit="createAlert" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create New Alert') }}</flux:heading>
                <flux:subheading>{{ __('Compose an SMS alert for farmers.') }}</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:textarea
                    wire:model="message"
                    label="{{ __('Message Text') }}"
                    placeholder="{{ __('Enter alert message...') }}"
                    rows="4"
                    required
                    maxlength="160"
                    helper="{{ __('Maximum 160 characters for SMS.') }}"
                />

                <flux:select wire:model="region" label="{{ __('Target Region') }}" required>
                    <flux:select.option value="">{{ __('All Regions') }}</flux:select.option>
                    <flux:select.option value="Dodoma">Dodoma</flux:select.option>
                    <flux:select.option value="Arusha">Arusha</flux:select.option>
                    <flux:select.option value="Mwanza">Mwanza</flux:select.option>
                    <flux:select.option value="Kilimanjaro">Kilimanjaro</flux:select.option>
                    <flux:select.option value="Tanga">Tanga</flux:select.option>
                </flux:select>

                <flux:select wire:model="crop_type" label="{{ __('Target Crop') }}" required>
                    <flux:select.option value="">{{ __('All Crops') }}</flux:select.option>
                    <flux:select.option value="Maize">Maize</flux:select.option>
                    <flux:select.option value="Coffee">Coffee</flux:select.option>
                    <flux:select.option value="Cassava">Cassava</flux:select.option>
                    <flux:select.option value="Cotton">Cotton</flux:select.option>
                    <flux:select.option value="Cashews">Cashews</flux:select.option>
                </flux:select>

                <flux:input type="datetime-local" wire:model="schedule_time" label="{{ __('Schedule Time') }}" />
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="ml-2">{{ __('Schedule Alert') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
