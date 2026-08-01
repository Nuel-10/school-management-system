@extends('layouts.public')

@section('title', 'Home')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-5xl font-bold text-blue-700">
            School Management System
        </h1>

        <p class="mt-4 text-gray-600 text-lg">
            Welcome to our modern school management platform.
        </p>

        <button class="mt-6 px-6 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800">
            Get Started
        </button>
    </div>
</div>

@endsection