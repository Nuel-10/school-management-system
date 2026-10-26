<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassLevel;
use App\Models\SchoolSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassLevelController extends Controller
{
    /**
     * Display a listing of class levels.
     */
    public function index()
    {
        $classLevels = ClassLevel::with(['schoolSection', 'classArms'])
            ->orderBy('school_section_id')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.class-levels.index', compact('classLevels'));
    }

    /**
     * Show the form for creating a new class level.
     */
    public function create()
    {
        $schoolSections = SchoolSection::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.class-levels.create', compact('schoolSections'));
    }

    /**
     * Store a newly created class level.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => [
                'required',
                'exists:school_sections,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_levels', 'name')
                    ->where('school_section_id', $request->school_section_id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],
            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        ClassLevel::create($validated);

        return redirect()
            ->route('admin.class-levels.index')
            ->with('success', 'Class level created successfully.');
    }

    /**
     * Display the specified class level.
     */
    public function show(ClassLevel $classLevel)
    {
        $classLevel->load([
            'schoolSection',
            'classArms.students',
        ]);

        return view('admin.class-levels.show', compact('classLevel'));
    }

    /**
     * Show the form for editing a class level.
     */
    public function edit(ClassLevel $classLevel)
    {
        $schoolSections = SchoolSection::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.class-levels.edit', compact(
            'classLevel',
            'schoolSections'
        ));
    }

    /**
     * Update the specified class level.
     */
    public function update(Request $request, ClassLevel $classLevel)
    {
        $validated = $request->validate([
            'school_section_id' => [
                'required',
                'exists:school_sections,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_levels', 'name')
                    ->where('school_section_id', $request->school_section_id)
                    ->ignore($classLevel->id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
            ],
            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $classLevel->update($validated);

        return redirect()
            ->route('class-levels.index')
            ->with('success', 'Class level updated successfully.');
    }

    /**
     * Remove the specified class level.
     */
    public function destroy(ClassLevel $classLevel)
    {
        $classLevel->delete();

        return redirect()
            ->route('class-levels.index')
            ->with('success', 'Class level deleted successfully.');
    }
}