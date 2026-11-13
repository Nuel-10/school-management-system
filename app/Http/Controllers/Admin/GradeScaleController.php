<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeScale;
use App\Models\SchoolSection;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GradeScaleController extends Controller
{
    public function index()
    {
        $gradeScales = GradeScale::with(['schoolSection', 'boundaries'])
            ->latest()
            ->get();

        return view('admin.grade-scales.index', compact('gradeScales'));
    }

    public function create()
    {
        $schoolSections = SchoolSection::orderBy('name')->get();

        return view('admin.grade-scales.create', compact('schoolSections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => ['required', 'exists:school_sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'boundaries' => ['required', 'array', 'min:1'],
            'boundaries.*.grade' => ['required', 'string', 'max:20'],
            'boundaries.*.minimum_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'boundaries.*.maximum_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'boundaries.*.remark' => ['required', 'string', 'max:255'],
        ]);

        $this->validateBoundaries($validated['boundaries']);

        DB::transaction(function () use ($validated, $request) {
            $isActive = $request->boolean('is_active', true);

            // Only one grading scale can be active per school section.
            if ($isActive) {
                GradeScale::where('school_section_id', $validated['school_section_id'])
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $gradeScale = GradeScale::create([
                'school_section_id' => $validated['school_section_id'],
                'name' => $validated['name'],
                'is_active' => $isActive,
            ]);

            foreach ($validated['boundaries'] as $index => $boundary) {
                $gradeScale->boundaries()->create([
                    'grade' => strtoupper(trim($boundary['grade'])),
                    'minimum_percentage' => $boundary['minimum_percentage'],
                    'maximum_percentage' => $boundary['maximum_percentage'],
                    'remark' => $boundary['remark'],
                    'display_order' => $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('grade-scales.index')
            ->with('success', 'Grading scale created successfully.');
    }

    public function edit(GradeScale $gradeScale)
    {
        // Protect grading scales already used by saved results.
        if (StudentResult::where('grade_scale_id', $gradeScale->id)->exists()) {
            return redirect()
                ->route('grade-scales.index')
                ->with('error', 'This grading scale has already been used for student results. Create a new grading scale for future changes.');
        }

        $gradeScale->load('boundaries');

        $schoolSections = SchoolSection::orderBy('name')->get();

        return view('admin.grade-scales.edit', compact(
            'gradeScale',
            'schoolSections'
        ));
    }

    public function update(Request $request, GradeScale $gradeScale)
    {
        $validated = $request->validate([
            'school_section_id' => ['required', 'exists:school_sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'boundaries' => ['required', 'array', 'min:1'],
            'boundaries.*.grade' => ['required', 'string', 'max:20'],
            'boundaries.*.minimum_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'boundaries.*.maximum_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'boundaries.*.remark' => ['required', 'string', 'max:255'],
        ]);

        $this->validateBoundaries($validated['boundaries']);

        DB::transaction(function () use ($validated, $request, $gradeScale) {
            // Recheck inside the transaction before changing historical grading rules.
            $used = StudentResult::where('grade_scale_id', $gradeScale->id)
                ->lockForUpdate()
                ->exists();

            if ($used) {
                throw ValidationException::withMessages([
                    'gradeScale' => 'This grading scale is already linked to student results and cannot be edited.',
                ]);
            }

            $isActive = $request->boolean('is_active', true);

            if ($isActive) {
                GradeScale::where('school_section_id', $validated['school_section_id'])
                    ->where('is_active', true)
                    ->where('id', '!=', $gradeScale->id)
                    ->update(['is_active' => false]);
            }

            $gradeScale->update([
                'school_section_id' => $validated['school_section_id'],
                'name' => $validated['name'],
                'is_active' => $isActive,
            ]);

            // Safe because this scale has not been used by any saved result.
            $gradeScale->boundaries()->delete();

            foreach ($validated['boundaries'] as $index => $boundary) {
                $gradeScale->boundaries()->create([
                    'grade' => strtoupper(trim($boundary['grade'])),
                    'minimum_percentage' => $boundary['minimum_percentage'],
                    'maximum_percentage' => $boundary['maximum_percentage'],
                    'remark' => $boundary['remark'],
                    'display_order' => $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('grade-scales.index')
            ->with('success', 'Grading scale updated successfully.');
    }

    public function destroy(GradeScale $gradeScale)
    {
        if (StudentResult::where('grade_scale_id', $gradeScale->id)->exists()) {
            return redirect()
                ->route('grade-scales.index')
                ->with('error', 'This grading scale cannot be deleted because student results reference it.');
        }

        $gradeScale->delete();

        return redirect()
            ->route('grade-scales.index')
            ->with('success', 'Grading scale deleted successfully.');
    }

    private function validateBoundaries(array $boundaries): void
    {
        $grades = [];
        $ranges = [];

        foreach ($boundaries as $boundary) {
            $grade = strtoupper(trim($boundary['grade']));
            $minimum = (float) $boundary['minimum_percentage'];
            $maximum = (float) $boundary['maximum_percentage'];

            if ($minimum > $maximum) {
                throw ValidationException::withMessages([
                    'boundaries' => "The minimum percentage cannot exceed the maximum for grade {$grade}.",
                ]);
            }

            if (in_array($grade, $grades, true)) {
                throw ValidationException::withMessages([
                    'boundaries' => "The grade {$grade} has been entered more than once.",
                ]);
            }

            $grades[] = $grade;

            $ranges[] = [
                'minimum' => $minimum,
                'maximum' => $maximum,
                'grade' => $grade,
            ];
        }

        // Prevent overlapping grade ranges.
        for ($i = 0; $i < count($ranges); $i++) {
            for ($j = $i + 1; $j < count($ranges); $j++) {
                $overlap = $ranges[$i]['minimum'] <= $ranges[$j]['maximum']
                    && $ranges[$j]['minimum'] <= $ranges[$i]['maximum'];

                if ($overlap) {
                    throw ValidationException::withMessages([
                        'boundaries' => 'Grade percentage ranges must not overlap.',
                    ]);
                }
            }
        }
    }
}
