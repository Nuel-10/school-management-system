<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSection;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $schoolSections = SchoolSection::where('is_active', true)->get();


        return view('admin.dashboard', compact('schoolSections'));
    }
}