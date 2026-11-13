<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SchoolSection;
use App\Models\ClassLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['schoolSection', 'classLevels'])
            ->orderBy('name')
            ->paginate(15);

        $schoolSections = SchoolSection::where('is_active', true)
            ->with(['classLevels' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('display_order');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.subjects.index', compact(
            'subjects',
            'schoolSections'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => ['required', 'exists:school_sections,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'name')
                    ->where('school_section_id', $request->school_section_id),
            ],
            'code' => ['nullable', 'string', 'max:50'],
            'class_level_ids' => ['required', 'array', 'min:1'],
            'class_level_ids.*' => ['required', 'exists:class_levels,id'],
            'is_compulsory' => ['required', 'boolean'],
        ]);

        $classLevels = ClassLevel::whereIn(
            'id',
            $validated['class_level_ids']
        )->where('school_section_id', $validated['school_section_id'])->get();

        if ($classLevels->count() !== count($validated['class_level_ids'])) {
            return back()
                ->withErrors(['class_level_ids' => 'Select valid classes from the chosen school section.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            $subject = Subject::create([
                'school_section_id' => $validated['school_section_id'],
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'is_active' => true,
            ]);

            foreach ($validated['class_level_ids'] as $classLevelId) {
                $subject->classLevels()->attach($classLevelId, [
                    'is_compulsory' => $validated['is_compulsory'],
                ]);
            }
        });

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject created and assigned successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
           ->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}