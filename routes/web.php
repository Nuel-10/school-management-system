<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/about', function () {
    return view('pages.about');
});

Route::get('/admissions', function () {
    return view('pages.admissions');
});

Route::get('/contact', function () {
    return view('pages.contact');
});

require __DIR__.'/auth.php';
