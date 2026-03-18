@extends('layouts.app-layout')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-green-950">SMS Inbox Demo</h1>
                <p class="text-gray-600 mt-1">Preview how the farmer receives the message on their phone.</p>
            </div>

            <form method="GET" action="{{ route('sms.inbox') }}" class="flex items-center gap-3">
                <label for="farmer_id" class="text-sm text-gray-600">Farmer</label>
                <select
                    name="farmer_id"
                    id="farmer_id"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}" {{ optional($selectedFarmer)->id == $farmer->id ? 'selected' : '' }}>
                            {{ $farmer->full_name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold text-green-950 mb-4">Message Details</h2>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Farmer:</span>
                        <span id="details-farmer" class="font-medium text-gray-900">
                            {{ $latestMessage->farmer_name ?? ($selectedFarmer->full_name ?? 'Farmer Demo') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500">Phone:</span>
                        <span id="details-phone" class="font-medium text-gray-900">
                            {{ $latestMessage->phone_number ?? ($selectedFarmer->phone_number ?? '+255 XXX XXX XXX') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500">Language:</span>
                        <span id="details-language" class="font-medium text-gray-900">
                            {{ $latestMessage->language ?? ($selectedFarmer->preferred_language ?? 'English') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500">Sent at:</span>
                        <span id="details-time" class="font-medium text-gray-900">
                            {{ optional($latestMessage?->sent_at)->format('H:i') ?? now()->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="w-[320px] rounded-[2.5rem] bg-gray-900 p-3 shadow-2xl">
                    <div class="rounded-[2rem] bg-gray-100 min-h-[620px] overflow-hidden border border-gray-800">
                        <div class="bg-green-800 text-white px-4 py-4">
                            <div class="text-sm font-semibold">AgriBridge SMS</div>
                            <div id="phone-farmer" class="text-xs text-green-100 mt-1">
                                {{ $latestMessage->farmer_name ?? ($selectedFarmer->full_name ?? 'Farmer Demo') }}
                            </div>
                        </div>

                        <div class="p-4 space-y-4 bg-stone-100 min-h-[540px]">
                            <div class="flex justify-start">
                                <div class="max-w-[85%] rounded-2xl rounded-tl-md bg-white px-4 py-3 shadow text-sm text-gray-800">
                                    <div id="phone-message">
                                        {{ $latestMessage->message ?? 'No SMS message available yet.' }}
                                    </div>
                                    <div id="phone-time" class="text-[11px] text-gray-400 mt-2 text-right">
                                        {{ optional($latestMessage?->sent_at)->format('H:i') ?? now()->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-500">
            Inbox refreshes automatically every 2 seconds.
        </div>
    </div>

    <script>
        let lastMessageId = {{ $latestMessage->id ?? 0 }};

        async function pollLatestMessage() {
            const farmerId = document.getElementById('farmer_id')?.value;

            if (!farmerId) return;

            try {
                const response = await fetch(`{{ route('sms.inbox.latest') }}?farmer_id=${farmerId}`);
                const data = await response.json();

                if (!data.found) return;

                if (data.id !== lastMessageId) {
                    lastMessageId = data.id;

                    document.getElementById('details-farmer').textContent = data.farmer_name;
                    document.getElementById('details-phone').textContent = data.phone_number;
                    document.getElementById('details-language').textContent = data.language;
                    document.getElementById('details-time').textContent = data.sent_at;

                    document.getElementById('phone-farmer').textContent = data.farmer_name;
                    document.getElementById('phone-message').textContent = data.message;
                    document.getElementById('phone-time').textContent = data.sent_at;
                }
            } catch (error) {
                console.error('Polling failed:', error);
            }
        }

        setInterval(pollLatestMessage, 2000);
    </script>
@endsection
