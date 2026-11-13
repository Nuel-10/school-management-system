
<?php

use App\Http\Controllers\Admin\StudentResultController;
use App\Http\Controllers\Admin\StudentResultApprovalController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\StudentPromotionController;
use App\Http\Controllers\Admin\AssessmentSchemeController;
use App\Http\Controllers\Admin\GradeScaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ReportCardSettingController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', fn () => view('pages.home'));
Route::get('/about', fn () => view('pages.about'));
Route::get('/admissions', fn () => view('pages.admissions'));
Route::get('/contact', fn () => view('pages.contact'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth'])
    ->name('dashboard');

// Authenticated admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Results entry
    Route::get('/results', [StudentResultController::class, 'index'])
        ->name('results.index');

    Route::post('/results/students', [StudentResultController::class, 'students'])
        ->name('results.students');

    Route::post('/results/save', [StudentResultController::class, 'save'])
        ->name('results.save');

    // Results approval management
    Route::get('/results/approvals', [StudentResultApprovalController::class, 'index'])
        ->name('results.approvals.index');

    // Individual result actions
    Route::post('/results/{studentResult}/submit', [StudentResultApprovalController::class, 'submit'])
        ->name('results.submit');

    Route::post('/results/{studentResult}/approve', [StudentResultApprovalController::class, 'approve'])
        ->name('results.approve');

    Route::post('/results/{studentResult}/unapprove', [StudentResultApprovalController::class, 'unapprove'])
        ->name('results.unapprove');

    // Class/group result actions
    Route::post('/results/approvals/bulk-submit', [StudentResultApprovalController::class, 'bulkSubmit'])
        ->name('results.approvals.bulk-submit');

    Route::post('/results/approvals/bulk-approve', [StudentResultApprovalController::class, 'bulkApprove'])
        ->name('results.approvals.bulk-approve');

    Route::post('/results/approvals/bulk-unapprove', [StudentResultApprovalController::class, 'bulkUnapprove'])
        ->name('results.approvals.bulk-unapprove');

    // Assessment schemes
    Route::get('/assessment-schemes', [AssessmentSchemeController::class, 'index'])
        ->name('assessment-schemes.index');

    Route::post('/assessment-schemes', [AssessmentSchemeController::class, 'store'])
        ->name('assessment-schemes.store');

    Route::get('/assessment-schemes/{assessmentScheme}/edit', [AssessmentSchemeController::class, 'edit'])
        ->name('assessment-schemes.edit');

    Route::put('/assessment-schemes/{assessmentScheme}', [AssessmentSchemeController::class, 'update'])
        ->name('assessment-schemes.update');

    // Class levels, arms and students
    Route::resource('class-levels', \App\Http\Controllers\Admin\ClassLevelController::class);
    Route::resource('class-arms', \App\Http\Controllers\Admin\ClassArmController::class);
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // Academic sessions
    Route::get('/academic-sessions', [\App\Http\Controllers\Admin\AcademicSessionController::class, 'index'])
        ->name('academic-sessions.index');

    Route::post('/academic-sessions', [\App\Http\Controllers\Admin\AcademicSessionController::class, 'store'])
        ->name('academic-sessions.store');

    Route::put('/academic-sessions/{academicSession}', [\App\Http\Controllers\Admin\AcademicSessionController::class, 'update'])
        ->name('academic-sessions.update');

    // Grade scales
    Route::resource('grade-scales', GradeScaleController::class)
        ->except(['show']);

    // Student promotions
    Route::get('/promotions', [StudentPromotionController::class, 'index'])
        ->name('promotions.index');

    Route::post('/promotions', [StudentPromotionController::class, 'store'])
        ->name('promotions.store');

    // Subject management
    Route::get('/subjects', [SubjectController::class, 'index'])
        ->name('subjects.index');

    Route::post('/subjects', [SubjectController::class, 'store'])
        ->name('subjects.store');

    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
        ->name('subjects.destroy');

            // Report card settings
    Route::get('/report-cards/settings', [ReportCardSettingController::class, 'index'])
        ->name('report-cards.settings.index');

    Route::post('/report-cards/settings', [ReportCardSettingController::class, 'update'])
        ->name('report-cards.settings.update');
});

require __DIR__.'/auth.php';
