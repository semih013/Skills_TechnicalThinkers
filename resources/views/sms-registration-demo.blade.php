@extends('layouts.app-layout')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-green-900">SMS Registration</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Register a farmer using an SMS message format.
                    </p>
                </div>

                <a href="{{ route('farmers.index') }}"
                   class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50">
                    Back to Farmers
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-5 border">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Incoming SMS</h2>

                    <form method="POST" action="{{ route('sms.registration.demo.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="from" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input
                                type="text"
                                id="from"
                                name="from"
                                value="{{ old('from', '+255700333444') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required
                            >
                        </div>

                        <div>
                            <label for="text" class="block text-sm font-medium text-gray-700 mb-1">
                                SMS Message
                            </label>
                            <input
                                type="text"
                                id="text"
                                name="text"
                                value="{{ old('text', 'REG Maria Peter, Arusha, Village B, Swahili') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required
                            >
                        </div>

                        <div class="rounded-md bg-blue-50 border border-blue-200 p-3 text-sm text-blue-800">
                            <strong>Expected format:</strong><br>
                            REG Full Name, Region, Village, Language
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                                Send SMS
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 border">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Farmer Phone</h2>

                    <div class="max-w-xs mx-auto rounded-[2rem] border-4 border-gray-800 bg-black p-4 shadow-lg">
                        <div class="rounded-[1.5rem] bg-gray-900 p-4 min-h-[420px] flex flex-col justify-between">
                            <div>
                                <div class="text-center text-xs text-gray-400 mb-4">Messages</div>

                                <div class="flex justify-end">
                                    <div id="phoneMessageBubble" class="bg-green-600 text-white text-sm rounded-2xl rounded-br-md px-4 py-3 max-w-[220px] shadow">
                                        {{ old('text', 'REG Amina Juma, Arusha, Moshi Rural, English') }}
                                    </div>
                                </div>

                                @if(session('success'))
                                    <div class="flex justify-start mt-4">
                                        <div class="bg-gray-200 text-gray-900 text-sm rounded-2xl rounded-bl-md px-4 py-3 max-w-[220px] shadow">
                                            Registration successful.
                                        </div>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="flex justify-start mt-4">
                                        <div class="bg-gray-200 text-gray-900 text-sm rounded-2xl rounded-bl-md px-4 py-3 max-w-[220px] shadow">
                                            {{ session('error') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <div class="rounded-full bg-gray-800 px-4 py-2 text-xs text-gray-400 text-center">
                                    SMS Preview
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const smsInput = document.getElementById('text');
        const phoneBubble = document.getElementById('phoneMessageBubble');

        smsInput.addEventListener('input', function () {
            phoneBubble.textContent = smsInput.value;
        });
    </script>
@endsection
