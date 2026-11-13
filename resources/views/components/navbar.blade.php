
<nav class="relative z-50 w-full bg-blue-900 text-white shadow-lg">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-5">

        <a href="{{ url('/') }}" class="text-2xl font-bold text-white no-underline">
            School Management System
        </a>

        <div class="flex flex-wrap items-center gap-5">
            <a href="{{ url('/') }}" class="text-white no-underline hover:text-yellow-300">Home</a>
            <a href="{{ url('/about') }}" class="text-white no-underline hover:text-yellow-300">About</a>
            <a href="{{ url('/admissions') }}" class="text-white no-underline hover:text-yellow-300">Admissions</a>
            <a href="{{ url('/contact') }}" class="text-white no-underline hover:text-yellow-300">Contact</a>

            @guest
                <a href="{{ route('login') }}" class="rounded-lg bg-yellow-400 px-5 py-2 font-bold text-blue-950 no-underline hover:bg-yellow-300">
                    Login
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-yellow-400 px-5 py-2 font-bold text-blue-950 no-underline hover:bg-yellow-300">
                    My Dashboard
                </a>
            @endguest
        </div>

    </div>
</nav>
