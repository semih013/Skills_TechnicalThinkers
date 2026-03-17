<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 min-h-screen p-8">

<div class="max-w-6xl mx-auto bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-green-900">Registered Farmers</h1>
            <p class="text-gray-600 mt-1">Farmers eligible to receive alerts by region and language.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('farmers.create') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Add Farmer</a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">Back to Dashboard</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
            <tr class="border-b text-left text-gray-500">
                <th class="py-3 pr-4">Name</th>
                <th class="py-3 pr-4">Phone</th>
                <th class="py-3 pr-4">Region</th>
                <th class="py-3 pr-4">Village</th>
                <th class="py-3 pr-4">Language</th>
                <th class="py-3 pr-4">Wants SMS</th>
                <th class="py-3 pr-4 text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($farmers as $farmer)
                <tr class="border-b last:border-b-0">
                    <td class="py-3 pr-4 font-medium">{{ $farmer->full_name }}</td>
                    <td class="py-3 pr-4">{{ $farmer->phone_number }}</td>
                    <td class="py-3 pr-4">{{ $farmer->region }}</td>
                    <td class="py-3 pr-4">{{ $farmer->village ?? '-' }}</td>
                    <td class="py-3 pr-4">{{ $farmer->preferred_language }}</td>
                    <td class="py-3 pr-4">
                        @if($farmer->wants_sms)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Yes</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">No</span>
                        @endif
                    </td>
                    <td class="py-3 pr-4 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('farmers.edit', $farmer) }}" class="px-3 py-1 text-xs rounded-md border border-gray-300 hover:bg-gray-50">Edit</a>
                            <form action="{{ route('farmers.destroy', $farmer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this farmer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs rounded-md border border-red-300 text-red-700 hover:bg-red-50">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">
                        No farmers registered yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
