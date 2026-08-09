<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="font-sans antialiased bg-gray-100">


<div class="min-h-screen flex">


    <!-- SIDEBAR -->

    @include('components.sidebar')


    <!-- MAIN AREA -->

    <div class="flex-1 flex flex-col min-w-0">


        <!-- TOP BAR -->

        <header class="bg-white border-b border-gray-200 shadow-sm">

            <div class="px-6 py-4 flex items-center justify-between">


                <!-- Page Title -->

                <div>

                    <h1 class="text-xl font-bold text-gray-800">
                        School Management Dashboard
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Manage your school from one place
                    </p>

                </div>


                <!-- User Area -->

                <div class="flex items-center gap-5">


                    <!-- Notification -->

                    <button
                        type="button"
                        class="relative text-gray-500 hover:text-blue-600 transition"
                        title="Notifications"
                    >

                        <span class="text-xl">
                            🔔
                        </span>

                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center"
                        >
                            0
                        </span>

                    </button>


                    <!-- User -->

                    <div class="flex items-center gap-3">


                        <div
                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold"
                        >

                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                        </div>


                        <div class="hidden sm:block">

                            <p class="text-sm font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Administrator
                            </p>

                        </div>


                    </div>


                    <!-- Logout -->

                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition"
                        >
                            Logout
                        </button>

                    </form>


                </div>

            </div>

        </header>


        <!-- PAGE CONTENT -->

        <main class="flex-1 p-6">

            {{ $slot }}

        </main>


        <!-- FOOTER -->

        <footer class="bg-white border-t border-gray-200 px-6 py-4">

            <div class="flex flex-col sm:flex-row justify-between items-center gap-2">

                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} School Management System
                </p>

                <p class="text-xs text-gray-400">
                    Admin Portal
                </p>

            </div>

        </footer>


    </div>

</div>


</body>

</html>