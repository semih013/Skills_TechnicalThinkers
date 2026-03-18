@extends('layouts.app-layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-green-950">Create Alert</h1>
                    <p class="text-gray-600 mt-1">Create an SMS alert by region or for an individual farmer.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">
                    Back to Dashboard
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('alerts.preview') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2">Send Mode</label>
                    <select id="send_mode" name="send_mode" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="region" {{ old('send_mode', 'region') == 'region' ? 'selected' : '' }}>By Region</option>
                        <option value="individual" {{ old('send_mode') == 'individual' ? 'selected' : '' }}>Individual Farmer</option>
                    </select>
                </div>

                <div id="region-field">
                    <label class="block text-sm font-medium mb-2">Region</label>
                    <select name="region" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="">Select region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}" {{ old('region', $selectedRegion ?? '') == $region ? 'selected' : '' }}>
                                {{ $region }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="farmer-field" class="hidden">
                    <label class="block text-sm font-medium mb-2">Farmer</label>
                    <select name="farmer_id" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="">Select farmer</option>
                        @foreach($farmers as $farmer)
                            <option value="{{ $farmer->id }}" {{ old('farmer_id') == $farmer->id ? 'selected' : '' }}>
                                {{ $farmer->full_name }} - {{ $farmer->region }} - {{ $farmer->phone_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Alert Type</label>
                    <select name="alert_type" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="">Select alert type</option>
                        <option value="weather" {{ old('alert_type') == 'weather' ? 'selected' : '' }}>Weather</option>
                        <option value="pest" {{ old('alert_type') == 'pest' ? 'selected' : '' }}>Pest</option>
                        <option value="market" {{ old('alert_type') == 'market' ? 'selected' : '' }}>Market</option>
                        <option value="advisory" {{ old('alert_type') == 'advisory' ? 'selected' : '' }}>Advisory</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">SMS Message</label>

                    <div class="mb-3 flex flex-wrap gap-2">
                        @if(!empty($suggestedAlert))
                            <button
                                type="button"
                                onclick="setMessage(@js($suggestedAlert))"
                                class="text-sm px-3 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">
                                🌦 Use Suggested Weather Alert
                            </button>
                        @endif

                        <button
                            type="button"
                            onclick="appendMessage('Market price for maize is decreasing. Consider selling early or storing safely.')"
                            class="text-sm px-3 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200">
                            📊 Add Maize Price Alert
                        </button>
                    </div>

                    @if(!empty($suggestedAlert))
                        <div class="mb-3 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-sm">
                            <span class="font-semibold">Suggested:</span> {{ $suggestedAlert }}
                        </div>
                    @endif

                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        maxlength="250"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                        placeholder="Write your SMS alert here...">{{ old('message') }}</textarea>

                    <p class="text-sm text-gray-500 mt-1">
                        Review the suggested alert, then edit or add more information before preview.
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg shadow">
                        Preview Alert
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const sendMode = document.getElementById('send_mode');
        const regionField = document.getElementById('region-field');
        const farmerField = document.getElementById('farmer-field');

        function toggleFields() {
            if (sendMode.value === 'individual') {
                regionField.classList.add('hidden');
                farmerField.classList.remove('hidden');
            } else {
                regionField.classList.remove('hidden');
                farmerField.classList.add('hidden');
            }
        }

        function setMessage(text) {
            document.getElementById('message').value = text;
        }

        function appendMessage(text) {
            const textarea = document.getElementById('message');
            const current = textarea.value.trim();

            if (current.length === 0) {
                textarea.value = text;
            } else if (!current.includes(text)) {
                textarea.value = current + ' ' + text;
            }
        }

        sendMode.addEventListener('change', toggleFields);
        toggleFields();
    </script>
@endsection
