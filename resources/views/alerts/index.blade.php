<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-gray-800 min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-64 bg-green-900 text-white p-6 hidden md:block">
        <h1 class="text-2xl font-bold mb-8">AgriBridge</h1>

        <nav class="space-y-3">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-green-800">Dashboard</a>
            <a href="{{ route('submissions.index') }}" class="block px-4 py-2 rounded hover:bg-green-800">Submissions</a>
            <a href="{{ route('alerts.index') }}" class="block px-4 py-2 rounded bg-green-800">Alerts</a>
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
                <h2 class="text-3xl font-bold text-green-950">Alerts</h2>
                <p class="text-gray-600 mt-1">View all alerts sent to farmers.</p>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="{{ route('alerts.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg shadow">
                    + New Alert
                </a>
            </div>
        </div>

        <section class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-green-950">All Alerts</h3>
                <span class="text-sm text-gray-500">{{ $alerts->count() }} total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="py-3 pr-4">Region</th>
                        <th class="py-3 pr-4">Type</th>
                        <th class="py-3 pr-4">Message</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($alerts as $alert)
                        <tr class="border-b last:border-b-0">
                            <td class="py-3 pr-4 font-medium">{{ $alert->region }}</td>
                            <td class="py-3 pr-4 capitalize">{{ $alert->alert_type }}</td>
                            <td class="py-3 pr-4">{{ $alert->message }}</td>
                            <td class="py-3 pr-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($alert->status === 'sent')
                                            bg-green-100 text-green-700
                                        @else
                                            bg-yellow-100 text-yellow-700
                                        @endif">
                                        {{ ucfirst($alert->status) }}
                                    </span>
                            </td>
                            <td class="py-3">
                                {{ $alert->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                No alerts found.
                            </td>
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
