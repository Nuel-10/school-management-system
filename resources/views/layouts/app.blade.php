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


<body class="font-sans antialiased bg-gray-100">


<div class="min-h-screen flex">


    <!-- Sidebar -->
    @include('components.sidebar')



    <!-- Main Area -->
    <div class="flex-1">


        <!-- Top Bar -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h2 class="text-xl font-semibold text-gray-800">
                School Management Dashboard
            </h2>


            <div class="flex items-center gap-4">

                <span class="text-gray-700">
                    {{ Auth::user()->name }}
                </span>


                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="text-red-600 hover:text-red-800">
                        Logout
                    </button>

                </form>

            </div>

        </header>



        <!-- Page Content -->
        <main class="p-6">

            {{ $slot }}

        </main>


    </div>


</div>


</body>

</html>