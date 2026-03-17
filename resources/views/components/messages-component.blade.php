<?php

use Livewire\Component;
use App\Models\Message;

new class extends Component
{
    public function with(): array
    {
        return [
            'messages' => Message::latest()->paginate(15),
        ];
    }
};
?>

<div class="space-y-6">
    <flux:heading size="xl">{{ __('Message History') }}</flux:heading>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Recipient / Phone') }}</flux:table.column>
                <flux:table.column>{{ __('Message Content') }}</flux:table.column>
                <flux:table.column>{{ __('Type') }}</flux:table.column>
                <flux:table.column>{{ __('Sent Date') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($messages as $message)
                    <flux:table.row>
                        <flux:table.cell>
                            {{ $message->phone_number ?: $message->recipient_group }}
                        </flux:table.cell>
                        <flux:table.cell class="max-w-xl">{{ $message->content }}</flux:table.cell>
                        <flux:table.cell>{{ $message->recipient_group ?: 'Direct' }}</flux:table.cell>
                        <flux:table.cell>{{ $message->sent_at?->format('M d, Y H:i') ?? '-' }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="match($message->status) {
                                'Delivered', 'Sent' => 'green',
                                'Failed' => 'red',
                                'Pending' => 'yellow',
                                default => 'zinc'
                            }">
                                {{ $message->status }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>
    </flux:card>
</div>
