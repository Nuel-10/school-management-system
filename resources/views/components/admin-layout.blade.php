@props(['title' => 'Admin Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }} - School Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen">

        {{-- Admin Header --}}
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <h1 class="text-xl font-bold">
                    School Management System
                </h1>

                <div class="text-sm text-gray-600">
                    {{ Auth::user()->name }}
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main>
            {{ $slot }}
        </main>

    </div>

</body>
</html>