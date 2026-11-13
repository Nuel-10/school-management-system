
@extends('layouts.public')

@section('title', 'Home')

@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-950 via-blue-800 to-blue-700 text-white">
        <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32">
            <div class="max-w-3xl">

                <p class="mb-5 inline-block rounded-full border border-blue-300/40 bg-white/10 px-4 py-2 text-sm font-semibold tracking-wide text-blue-100">
                    LEARNING TODAY, LEADING TOMORROW
                </p>

                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                    Welcome to
                    <span class="text-yellow-400">Our School</span>
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-blue-100 sm:text-xl">
                    A school community committed to quality education,
                    academic excellence, character development and
                    preparing students for a brighter future.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="{{ url('/admissions') }}"
                       class="rounded-lg bg-yellow-400 px-7 py-3.5 font-bold text-blue-950 shadow-lg transition hover:bg-yellow-300">
                        Explore Admissions →
                    </a>

                    <a href="{{ url('/about') }}"
                       class="rounded-lg border border-white/50 px-7 py-3.5 font-semibold text-white transition hover:bg-white hover:text-blue-900">
                        Learn About Us
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Welcome Section --}}
    <section class="mx-auto max-w-7xl px-6 py-16 sm:py-20">
        <div class="mx-auto max-w-3xl text-center">

            <p class="font-bold uppercase tracking-widest text-blue-700">
                Welcome
            </p>

            <h2 class="mt-3 text-3xl font-bold text-gray-900 sm:text-4xl">
                Building Knowledge, Skills and Character
            </h2>

            <p class="mt-5 text-lg leading-8 text-gray-600">
                We aim to provide a supportive learning environment where
                students can develop their abilities, build confidence and
                prepare for future opportunities.
            </p>

        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">

            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                    📚
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900">
                    Quality Education
                </h3>

                <p class="mt-3 leading-7 text-gray-600">
                    Encourage understanding, curiosity, critical thinking
                    and a lifelong love of learning.
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100 text-2xl">
                    🎯
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900">
                    Student Development
                </h3>

                <p class="mt-3 leading-7 text-gray-600">
                    Support students as they develop responsibility,
                    confidence, discipline and practical skills.
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">
                    💻
                </div>

                <h3 class="mt-5 text-xl font-bold text-gray-900">
                    Digital Learning
                </h3>

                <p class="mt-3 leading-7 text-gray-600">
                    Promote responsible technology use and prepare
                    learners for an increasingly digital world.
                </p>
            </div>

        </div>
    </section>

    {{-- Admissions Call to Action --}}
    <section class="bg-blue-50">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 py-14 text-center md:flex-row md:text-left">

            <div>
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    Take the Next Step
                </h2>

                <p class="mt-3 max-w-2xl leading-7 text-gray-600">
                    Find out more about our school and the admissions process.
                    Contact us if you have questions.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ url('/admissions') }}"
                   class="rounded-lg bg-blue-700 px-6 py-3 font-semibold text-white transition hover:bg-blue-800">
                    Admissions
                </a>

                <a href="{{ url('/contact') }}"
                   class="rounded-lg border border-blue-700 px-6 py-3 font-semibold text-blue-700 transition hover:bg-blue-100">
                    Contact Us
                </a>
            </div>

        </div>
    </section>

</div>

@endsection
