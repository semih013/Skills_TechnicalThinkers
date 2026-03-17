@extends('layouts.app-layout')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-green-900">Registered Farmers</h1>
                    <p class="text-gray-600 mt-1">Farmers eligible to receive alerts by region and language.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">
                    Back to Dashboard
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="py-3 pr-4">Name</th>
                        <th class="py-3 pr-4">Phone</th>
                        <th class="py-3 pr-4">Region</th>
                        <th class="py-3 pr-4">Village</th>
                        <th class="py-3 pr-4">Language</th>
                        <th class="py-3">Wants SMS</th>
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
                            <td class="py-3">
                                @if($farmer->wants_sms)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    Yes
                                </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    No
                                </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                No farmers registered yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
