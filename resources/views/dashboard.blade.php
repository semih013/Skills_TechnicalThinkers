@extends('layouts.app-layout')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-green-950">Analyst Dashboard</h2>
            <p class="text-gray-600 mt-1">Monitor incoming field reports and create timely alerts.</p>
        </div>

        <div class="mt-4 md:mt-0 flex gap-3">
            <a href="{{ route('alerts.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow">
                + New Alert
            </a>
            <a href="{{ route('submissions.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                View Submissions
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-green-700">
            <p class="text-sm text-gray-500">Total Reports</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalReports }}</h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-amber-500">
            <p class="text-sm text-gray-500">Alerts Sent</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalAlerts }}</h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-red-600">
            <p class="text-sm text-gray-500">High-Risk Reports</p>
            <h3 class="text-3xl font-bold mt-2 text-red-600">{{ $highRiskCount }}</h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-orange-500">
            <p class="text-sm text-gray-500">Pest Reports</p>
            <h3 class="text-3xl font-bold mt-2 text-orange-600">{{ $pestReports }}</h3>
        </div>
    </div>

    <section class="bg-white rounded-2xl shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
            <div>
                <h3 class="text-xl font-semibold text-green-950">7-Day Weather Forecast</h3>
                <p class="text-sm text-gray-500 mt-1">Live forecast for {{ $selectedRegion }}</p>
            </div>

            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
                <label for="region" class="text-sm text-gray-600">Region</label>
                <select
                    name="region"
                    id="region"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-4 py-2 bg-white">
                    @foreach(array_keys($regionCoordinates) as $region)
                        <option value="{{ $region }}" {{ $selectedRegion === $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(!empty($forecast) && count($forecast) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                @foreach($forecast as $day)
                    <div class="rounded-xl border border-gray-200 bg-stone-50 p-4">
                        <p class="text-sm text-gray-500">{{ $day['day'] ?? '-' }}</p>
                        <p class="text-xs text-gray-400 mb-3">
                            {{ !empty($day['date']) ? \Carbon\Carbon::parse($day['date'])->format('d M') : '-' }}
                        </p>

                        <div class="text-3xl mb-2">
                            @if(($day['condition'] ?? '') === 'Clear')
                                ☀️
                            @elseif(($day['condition'] ?? '') === 'Cloudy')
                                ☁️
                            @elseif(($day['condition'] ?? '') === 'Rain' || ($day['condition'] ?? '') === 'Rain Showers')
                                🌧️
                            @elseif(($day['condition'] ?? '') === 'Thunderstorm')
                                ⛈️
                            @elseif(($day['condition'] ?? '') === 'Drizzle')
                                🌦️
                            @elseif(($day['condition'] ?? '') === 'Fog')
                                🌫️
                            @elseif(($day['condition'] ?? '') === 'Snow' || ($day['condition'] ?? '') === 'Snow Showers')
                                ❄️
                            @else
                                🌤️
                            @endif
                        </div>

                        <p class="font-semibold text-green-950">{{ $day['condition'] ?? 'Unknown' }}</p>

                        @php
                            $tempMax = $day['temp_max'] ?? null;
                            $tempMin = $day['temp_min'] ?? null;
                        @endphp

                        <p class="text-sm text-gray-600 mt-1">
                            @if($tempMax !== null && $tempMin !== null)
                                {{ round($tempMax) }}° / {{ round($tempMin) }}°
                            @else
                                -
                            @endif
                        </p>

                        <p class="text-sm text-blue-700 mt-1">
                            Rain: {{ $day['rain_chance'] ?? 0 }}%
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-100 text-yellow-800">
                Forecast data is currently unavailable.
            </div>
        @endif
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-green-950">Recent Field Reports</h3>
                <span class="text-sm text-gray-500">Latest 5 reports</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="py-3 pr-4">Region</th>
                        <th class="py-3 pr-4">Village</th>
                        <th class="py-3 pr-4">Crop</th>
                        <th class="py-3 pr-4">Rainfall</th>
                        <th class="py-3 pr-4">Condition</th>
                        <th class="py-3">Pest</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentSubmissions as $submission)
                        <tr class="border-b last:border-b-0">
                            <td class="py-3 pr-4 font-medium">{{ $submission->region }}</td>
                            <td class="py-3 pr-4">{{ $submission->village ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $submission->crop_type ?? '-' }}</td>
                            <td class="py-3 pr-4 capitalize">{{ $submission->rainfall_status ?? '-' }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($submission->crop_condition === 'poor')
                                        bg-red-100 text-red-700
                                    @elseif($submission->crop_condition === 'average')
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-green-100 text-green-700
                                    @endif">
                                    {{ ucfirst($submission->crop_condition ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($submission->pest_detected)
                                    <span class="text-red-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-green-600 font-semibold">No</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">No field reports yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold text-green-950 mb-4">Quick Insights</h3>

            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                    <p class="text-sm text-red-700 font-semibold">High Priority</p>
                    <p class="text-sm text-gray-700 mt-1">Poor crop condition reports may need immediate alerts.</p>
                </div>

                <div class="p-4 rounded-xl bg-orange-50 border border-orange-100">
                    <p class="text-sm text-orange-700 font-semibold">Pest Monitoring</p>
                    <p class="text-sm text-gray-700 mt-1">Repeated pest reports should trigger a regional warning.</p>
                </div>

                <div class="p-4 rounded-xl bg-green-50 border border-green-100">
                    <p class="text-sm text-green-700 font-semibold">System Flow</p>
                    <p class="text-sm text-gray-700 mt-1">Submission → Analysis → Alert → SMS.</p>
                </div>
            </div>
        </section>
    </div>

    <section class="bg-white rounded-2xl shadow p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-green-950">Recent Alerts</h3>
            <span class="text-sm text-gray-500">Latest 5 alerts</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="py-3 pr-4">Region</th>
                    <th class="py-3 pr-4">Type</th>
                    <th class="py-3 pr-4">Message</th>
                    <th class="py-3">Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentAlerts as $alert)
                    <tr class="border-b last:border-b-0">
                        <td class="py-3 pr-4 font-medium">{{ $alert->region }}</td>
                        <td class="py-3 pr-4 capitalize">{{ $alert->alert_type }}</td>
                        <td class="py-3 pr-4">{{ $alert->message }}</td>
                        <td class="py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($alert->status === 'sent')
                                    bg-green-100 text-green-700
                                @else
                                    bg-yellow-100 text-yellow-700
                                @endif">
                                {{ ucfirst($alert->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500">No alerts created yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
