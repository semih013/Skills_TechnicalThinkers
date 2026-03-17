<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Farmer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 min-h-screen p-8">
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-green-900">Edit Farmer</h1>
        <a href="{{ route('farmers.index') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">Back to Farmers</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4">
            <h2 class="text-sm font-semibold text-red-800">There were some problems with your input.</h2>
            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('farmers.update', $farmer) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $farmer->full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $farmer->phone_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="region">Region</label>
            <input type="text" id="region" name="region" value="{{ old('region', $farmer->region) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="village">Village</label>
            <input type="text" id="village" name="village" value="{{ old('village', $farmer->village) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="preferred_language">Preferred Language</label>
            <input type="text" id="preferred_language" name="preferred_language" value="{{ old('preferred_language', $farmer->preferred_language) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
        </div>

        <div class="flex items-center">
            <input type="checkbox" id="wants_sms" name="wants_sms" value="1" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500" {{ old('wants_sms', $farmer->wants_sms) ? 'checked' : '' }}>
            <label for="wants_sms" class="ml-2 block text-sm text-gray-700">Wants to receive SMS alerts</label>
        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('farmers.index') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Update Farmer</button>
        </div>
    </form>
</div>
</body>
</html>

