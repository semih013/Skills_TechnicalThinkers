<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Submissions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-gray-800 min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-64 bg-green-900 text-white p-6 hidden md:block">
        <h1 class="text-2xl font-bold mb-8">AgriBridge</h1>

        <nav class="space-y-3">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-green-800">Dashboard</a>
            <a href="{{ route('submissions.index') }}" class="block px-4 py-2 rounded bg-green-800">Submissions</a>
            <a href="{{ route('alerts.create') }}" class="block px-4 py-2 rounded hover:bg-green-800">Create Alert</a>
            <a href="{{ route('farmers.index') }}" class="block px-4 py-2 rounded hover:bg-green-800">Farmers</a>
        </nav>

        <div class="mt-10 text-sm text-green-100">
            <p class="font-semibold">Prototype Goal</p>
            <p class="mt-2">Turn field data into simple SMS alerts for farmers.</p>
        </div>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-green-950">Field Submissions</h2>
                <p class="text-gray-600 mt-1">Review all incoming field reports from the field.</p>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="{{ route('dashboard') }}" class="bg-white border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <section class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-green-950">All Reports</h3>
                <span class="text-sm text-gray-500">{{ $submissions->count() }} total</span>
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
                        <th class="py-3 pr-4">Pest</th>
                        <th class="py-3 pr-4">Market Price</th>
                        <th class="py-3">Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($submissions as $submission)
                        <tr class="border-b last:border-b-0 align-top">
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
                            <td class="py-3 pr-4">
                                @if($submission->pest_detected)
                                    <span class="text-red-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-green-600 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                {{ $submission->market_price !== null ? number_format($submission->market_price, 2) : '-' }}
                            </td>
                            <td class="py-3 text-gray-700">
                                {{ $submission->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">No submissions found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

</body>
</html>
