<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AgriBridge' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-gray-800 min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-64 bg-green-900 text-white p-6 hidden md:block">
        <h1 class="text-2xl font-bold mb-8">AgriBridge</h1>

        <nav class="space-y-3">
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                Dashboard
            </a>

            <a href="{{ route('submissions.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('submissions.*') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                Submissions
            </a>

            <a href="{{ route('alerts.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('alerts.index') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                Alerts
            </a>

            <a href="{{ route('alerts.create') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('alerts.create', 'alerts.preview') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                Create Alert
            </a>

            <a href="{{ route('farmers.index') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('farmers.*') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                Farmers
            </a>

            <a href="{{ route('sms.registration.demo') }}"
               class="block px-4 py-2 rounded {{ request()->routeIs('sms.registration.demo') ? 'bg-green-800' : 'hover:bg-green-800' }}">
                SMS Registration
            </a>
        </nav>

        <div class="mt-10 text-sm text-green-100">
            <p class="font-semibold">Prototype Goal</p>
            <p class="mt-2">Turn field data into simple SMS alerts for farmers.</p>
        </div>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
