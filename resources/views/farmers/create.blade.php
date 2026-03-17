@extends('layouts.app-layout')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-green-900">Add New Farmer</h1>
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

            @php($farmer = $farmer ?? new \App\Models\Farmer())
            @php($submitLabel = 'Save Farmer')

            <form method="POST" action="{{ route('farmers.store') }}" class="space-y-4">
                @include('farmers._form')
            </form>
        </div>
    </div>
@endsection
