<?php

use Livewire\Component;
use App\Models\Farmer;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url]
    public string $search = '';

    public string $name = '';
    public string $phone_number = '';
    public string $country = '';
    public string $region = '';
    public string $crop_type = '';
    public string $language = '';

    public function addFarmer(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string',
            'region' => 'required|string',
            'crop_type' => 'required|string',
            'language' => 'required|string',
        ]);

        Farmer::create($validated);

        $this->reset(['name', 'phone_number', 'country', 'region', 'crop_type', 'language']);
        $this->modal('add-farmer-modal')->close();
    }

    public function with(): array
    {
        return [
            'farmers' => Farmer::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('region', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Farmers') }}</flux:heading>

        <flux:modal.trigger name="add-farmer-modal">
            <flux:button variant="primary" icon="plus">{{ __('Add Farmer') }}</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="flex items-center gap-4">
        <flux:input icon="magnifying-glass" wire:model.live="search" placeholder="{{ __('Search farmers...') }}" class="max-w-md" />
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Phone') }}</flux:table.column>
                <flux:table.column>{{ __('Country') }}</flux:table.column>
                <flux:table.column>{{ __('Region') }}</flux:table.column>
                <flux:table.column>{{ __('Crop') }}</flux:table.column>
                <flux:table.column>{{ __('Language') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($farmers as $farmer)
                    <flux:table.row>
                        <flux:table.cell>{{ $farmer->name }}</flux:table.cell>
                        <flux:table.cell>{{ $farmer->phone_number }}</flux:table.cell>
                        <flux:table.cell>{{ $farmer->country }}</flux:table.cell>
                        <flux:table.cell>{{ $farmer->region }}</flux:table.cell>
                        <flux:table.cell>{{ $farmer->crop_type }}</flux:table.cell>
                        <flux:table.cell>{{ $farmer->language }}</flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $farmers->links() }}
        </div>
    </flux:card>

    <flux:modal name="add-farmer-modal" class="md:w-[500px]">
        <form wire:submit="addFarmer" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Add New Farmer') }}</flux:heading>
                <flux:subheading>{{ __('Enter the details of the farmer to add them to the system.') }}</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="name" label="{{ __('Name') }}" required />
                <flux:input wire:model="phone_number" label="{{ __('Phone Number') }}" required />

                <flux:select wire:model="country" label="{{ __('Country') }}" required>
                    <flux:select.option value="">{{ __('Select Country') }}</flux:select.option>
                    <flux:select.option value="Tanzania">Tanzania</flux:select.option>
                    <flux:select.option value="Kenya">Kenya</flux:select.option>
                    <flux:select.option value="Uganda">Uganda</flux:select.option>
                    <flux:select.option value="Rwanda">Rwanda</flux:select.option>
                </flux:select>

                <flux:select wire:model="region" label="{{ __('Region') }}" required>
                    <flux:select.option value="">{{ __('Select Region') }}</flux:select.option>
                    <flux:select.option value="Dodoma">Dodoma</flux:select.option>
                    <flux:select.option value="Arusha">Arusha</flux:select.option>
                    <flux:select.option value="Mwanza">Mwanza</flux:select.option>
                    <flux:select.option value="Kilimanjaro">Kilimanjaro</flux:select.option>
                    <flux:select.option value="Tanga">Tanga</flux:select.option>
                </flux:select>

                <flux:select wire:model="crop_type" label="{{ __('Crop Type') }}" required>
                    <flux:select.option value="">{{ __('Select Crop') }}</flux:select.option>
                    <flux:select.option value="Maize">Maize</flux:select.option>
                    <flux:select.option value="Coffee">Coffee</flux:select.option>
                    <flux:select.option value="Cassava">Cassava</flux:select.option>
                    <flux:select.option value="Cotton">Cotton</flux:select.option>
                    <flux:select.option value="Cashews">Cashews</flux:select.option>
                </flux:select>

                <flux:select wire:model="language" label="{{ __('Language') }}" required>
                    <flux:select.option value="">{{ __('Select Language') }}</flux:select.option>
                    <flux:select.option value="Swahili">Swahili</flux:select.option>
                    <flux:select.option value="English">English</flux:select.option>
                </flux:select>
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="ml-2">{{ __('Add Farmer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
