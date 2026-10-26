
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentPromotionController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('class-levels', \App\Http\Controllers\Admin\ClassLevelController::class);
    Route::resource('class-arms', \App\Http\Controllers\Admin\ClassArmController::class);
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // Student promotion routes
    Route::get('/promotions', [StudentPromotionController::class, 'index'])
        ->name('promotions.index');

    Route::post('/promotions', [StudentPromotionController::class, 'store'])
        ->name('promotions.store');
});

require __DIR__.'/auth.php';
