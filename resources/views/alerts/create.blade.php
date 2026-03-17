<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Alert</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-gray-800 min-h-screen">

<div class="max-w-4xl mx-auto py-10 px-6">
    <div class="bg-white rounded-2xl shadow p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-green-950">Create Alert</h1>
                <p class="text-gray-600 mt-1">Create an SMS alert for farmers in a selected region.</p>
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
                <label class="block text-sm font-medium mb-2">Region</label>
                <select name="region" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    <option value="">Select region</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>
                            {{ $region }}
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
                <textarea
                    name="message"
                    rows="4"
                    maxlength="160"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    placeholder="Write your SMS alert here...">{{ old('message') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Keep message short for SMS (max 160 characters).</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg shadow">
                    Preview Alert
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
