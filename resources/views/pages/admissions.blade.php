@extends('layouts.app')

@section('title', 'Admissions')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="max-w-3xl text-center px-6">

        <h1 class="text-4xl font-bold text-blue-700">
            Admissions
        </h1>

        <p class="mt-6 text-gray-600 text-lg">
            We welcome new students and provide a simple admission process
            for parents and guardians who want to join our school community.
        </p>

        <button class="mt-6 px-6 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
            Apply Now
        </button>

    </div>
</div>

@endsection