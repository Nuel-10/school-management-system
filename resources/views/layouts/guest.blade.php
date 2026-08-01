<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gradient-to-br from-blue-800 to-blue-500 flex items-center justify-center px-4">

        <div class="w-full max-w-md">

            <!-- School Branding -->
            <div class="text-center mb-8">

                <div class="mx-auto bg-white w-24 h-24 rounded-full shadow-lg flex items-center justify-center">

                    <span class="text-blue-700 text-4xl font-extrabold">
                        SMS
                    </span>

                </div>


                <h1 class="mt-5 text-3xl font-bold text-white">
                    School Management System
                </h1>


                <p class="mt-2 text-blue-100 text-sm">
                    Secure school administration portal
                </p>

            </div>


            <!-- Authentication Card -->
            <div class="bg-white rounded-xl shadow-2xl px-8 py-8">

                {{ $slot }}

            </div>


            <!-- Footer -->
            <p class="text-center text-blue-100 text-sm mt-6">
                © {{ date('Y') }} School Management System
            </p>

        </div>

    </div>

</body>

</html>