@extends('layouts.app-layout')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-green-950">SMS Preview</h1>
                    <p class="text-gray-600 mt-1">Review recipients before sending the alert.</p>
                </div>
                <a href="{{ route('alerts.create') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">
                    Back to Create Alert
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Send Mode</p>
                    <p class="text-lg font-semibold capitalize">{{ $alertData['send_mode'] }}</p>
                </div>

                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Region</p>
                    <p class="text-lg font-semibold">{{ $selectedRegion ?? '-' }}</p>
                </div>

                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Alert Type</p>
                    <p class="text-lg font-semibold capitalize">{{ $alertData['alert_type'] }}</p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Recipient Count</p>
                    <p class="text-lg font-semibold">{{ $recipients->count() }}</p>
                </div>
            </div>

            @if($selectedFarmer)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-green-950 mb-3">Selected Farmer</h2>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-800">
                        {{ $selectedFarmer->full_name }} - {{ $selectedFarmer->phone_number }}
                    </div>
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-green-950 mb-3">Original SMS Message</h2>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-800">
                    {{ $alertData['message'] }}
                </div>
            </div>

            @if(!empty($weatherSummary))
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-green-950 mb-3">Weather Summary Added Automatically</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-gray-800">
                        {{ $weatherSummary }}
                    </div>
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-green-950 mb-3">Final SMS Message</h2>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-gray-800">
                    {{ $finalMessage }}
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-green-950 mb-3">Recipients</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                        <tr class="border-b text-left text-gray-500">
                            <th class="py-3 pr-4">Name</th>
                            <th class="py-3 pr-4">Phone</th>
                            <th class="py-3 pr-4">Language</th>
                            <th class="py-3 pr-4">SMS Preview</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recipients as $farmer)
                            <tr class="border-b last:border-b-0">
                                <td class="py-3 pr-4 font-medium">{{ $farmer->full_name }}</td>
                                <td class="py-3 pr-4">{{ $farmer->phone_number }}</td>
                                <td class="py-3 pr-4">{{ $farmer->preferred_language }}</td>
                                <td class="py-3 pr-4 text-gray-700">{{ $farmer->translated_message }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-red-600">
                                    No farmers found with SMS enabled.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <form action="{{ route('alerts.send') }}" method="POST" class="flex justify-end">
                @csrf
                <input type="hidden" name="send_mode" value="{{ $alertData['send_mode'] }}">
                <input type="hidden" name="region" value="{{ $alertData['region'] ?? '' }}">
                <input type="hidden" name="farmer_id" value="{{ $alertData['farmer_id'] ?? '' }}">
                <input type="hidden" name="alert_type" value="{{ $alertData['alert_type'] }}">
                <input type="hidden" name="message" value="{{ $finalMessage }}">

                <button
                    type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg shadow {{ $recipients->count() === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $recipients->count() === 0 ? 'disabled' : '' }}>
                    Send Alert
                </button>
            </form>
        </div>
    </div>
@endsection
