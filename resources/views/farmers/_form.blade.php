@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="full_name">Full Name</label>
        <input
            type="text"
            id="full_name"
            name="full_name"
            value="{{ old('full_name', $farmer->full_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required
        >
        @error('full_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="phone_number">Phone Number</label>
        <input
            type="text"
            id="phone_number"
            name="phone_number"
            value="{{ old('phone_number', $farmer->phone_number ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required
        >
        @error('phone_number')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="region">Region</label>
        <input
            type="text"
            id="region"
            name="region"
            value="{{ old('region', $farmer->region ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required
        >
        @error('region')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="village">Village</label>
        <input
            type="text"
            id="village"
            name="village"
            value="{{ old('village', $farmer->village ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
        >
        @error('village')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="preferred_language">Preferred Language</label>
        <input
            type="text"
            id="preferred_language"
            name="preferred_language"
            value="{{ old('preferred_language', $farmer->preferred_language ?? 'English') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            required
        >
        @error('preferred_language')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center mt-6">
        <input
            type="checkbox"
            id="wants_sms"
            name="wants_sms"
            value="1"
            class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
            @checked(old('wants_sms', $farmer->wants_sms ?? true))
        >
        <label for="wants_sms" class="ml-2 block text-sm text-gray-700">
            Wants to receive SMS alerts
        </label>
        @error('wants_sms')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ route('farmers.index') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
        Cancel
    </a>

    <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">
        {{ $submitLabel }}
    </button>
</div>
