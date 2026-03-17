<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-gray-800 min-h-screen">

<div class="max-w-3xl mx-auto py-10 px-6">
    <div class="bg-white rounded-2xl shadow p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-green-950">SMS Test</h1>
                <p class="text-gray-600 mt-1">Send a test SMS using a single phone number and message.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sms.test.send') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="phone_number" class="block text-sm font-medium mb-2">Phone Number</label>
                <input
                    id="phone_number"
                    name="phone_number"
                    type="text"
                    value="{{ old('phone_number', '+32475408568') }}"
                    placeholder="e.g. +233555000111"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    required
                >
            </div>

            <div>
                <label for="message" class="block text-sm font-medium mb-2">Message</label>
                <textarea
                    id="message"
                    name="message"
                    rows="4"
                    maxlength="160"
                    placeholder="Write your test SMS message..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    required>{{ old('message', 'test') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Max 160 characters for a standard SMS.</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg shadow">
                    Send Test SMS
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

