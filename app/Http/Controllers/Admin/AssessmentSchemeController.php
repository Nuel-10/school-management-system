<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\AssessmentScheme;
use App\Models\ClassLevel;
use App\Models\SchoolSection;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssessmentSchemeController extends Controller
{
    public function index()
    {
        $schoolSections = SchoolSection::where('is_active', true)
            ->with(['classLevels' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('display_order');
            }])
            ->orderBy('name')
            ->get();

        $academicSessions = AcademicSession::where('is_active', true)
            ->orderByDesc('name')
            ->get();

        $schemes = AssessmentScheme::with([
            'schoolSection',
            'classLevel',
            'subject',
            'academicSession',
            'components',
        ])
            ->orderByDesc('created_at')
            ->paginate(15);
            $subjects = Subject::where('is_active', true)
    ->with('classLevels')
    ->get(['id', 'name', 'school_section_id']);

        return view('admin.assessment-schemes.index', compact(
    'schoolSections',
    'academicSessions',
    'schemes',
    'subjects'
));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => [
                'required',
                'exists:school_sections,id',
            ],
            'class_level_id' => [
                'required',
                'exists:class_levels,id',
            ],
            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],
            'academic_session_id' => [
                'required',
                'exists:academic_sessions,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'total_marks' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999.99',
            ],
            'components' => [
                'required',
                'array',
                'min:1',
            ],
            'components.*.name' => [
                'required',
                'string',
                'max:255',
            ],
            'components.*.component_type' => [
                'required',
                'in:ca,exam',
            ],
            'components.*.maximum_score' => [
                'required',
                'numeric',
                'gt:0',
                'max:99999.99',
            ],
        ]);

        $classLevel = ClassLevel::where(
            'school_section_id',
            $validated['school_section_id']
        )->find($validated['class_level_id']);

        if (!$classLevel) {
            throw ValidationException::withMessages([
                'class_level_id' => 'The selected class does not belong to the selected school section.',
            ]);
        }

        $subject = Subject::where(
            'school_section_id',
            $validated['school_section_id']
        )->where('is_active', true)->find($validated['subject_id']);

        if (!$subject || !$classLevel->subjects()
            ->where('subjects.id', $validated['subject_id'])
            ->exists()) {
            throw ValidationException::withMessages([
                'subject_id' => 'The selected subject is not assigned to this class and school section.',
            ]);
        }

        if (!AcademicSession::where('is_active', true)
            ->whereKey($validated['academic_session_id'])
            ->exists()) {
            throw ValidationException::withMessages([
                'academic_session_id' => 'Select an active academic session.',
            ]);
        }

        $examCount = collect($validated['components'])
            ->where('component_type', 'exam')
            ->count();

        if ($examCount !== 1) {
            throw ValidationException::withMessages([
                'components' => 'Each assessment scheme must contain exactly one examination component.',
            ]);
        }

        $componentTotal = collect($validated['components'])
            ->sum(fn ($component) => (float) $component['maximum_score']);

        if (abs($componentTotal - (float) $validated['total_marks']) > 0.001) {
            throw ValidationException::withMessages([
                'total_marks' => 'The total maximum marks must equal the sum of all assessment component maximum marks. Your components currently add up to ' . number_format($componentTotal, 2) . '.',
            ]);
        }

        $alreadyExists = AssessmentScheme::where(
            'class_level_id',
            $validated['class_level_id']
        )
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_session_id', $validated['academic_session_id'])
            ->exists();

        if ($alreadyExists) {
            throw ValidationException::withMessages([
                'subject_id' => 'An assessment scheme already exists for this subject, class and academic session.',
            ]);
        }

        DB::transaction(function () use ($validated) {
            $scheme = AssessmentScheme::create([
                'school_section_id' => $validated['school_section_id'],
                'class_level_id' => $validated['class_level_id'],
                'subject_id' => $validated['subject_id'],
                'academic_session_id' => $validated['academic_session_id'],
                'name' => $validated['name'],
                'total_marks' => $validated['total_marks'],
                'is_active' => true,
            ]);

            foreach ($validated['components'] as $index => $component) {
                $scheme->components()->create([
                    'name' => $component['name'],
                    'component_type' => $component['component_type'],
                    'maximum_score' => $component['maximum_score'],
                    'display_order' => $index + 1,
                ]);
            }
        });

        return redirect()
            ->route('assessment-schemes.index')
            ->with('success', 'Assessment scheme created successfully.');
    }
    public function edit(AssessmentScheme $assessmentScheme)
{
    $assessmentScheme->load([
        'schoolSection',
        'classLevel',
        'subject',
        'academicSession',
        'components',
    ]);

    $schoolSections = SchoolSection::where('is_active', true)
        ->with(['classLevels' => function ($query) {
            $query->where('is_active', true)
                ->orderBy('display_order');
        }])
        ->orderBy('name')
        ->get();

    $academicSessions = AcademicSession::where('is_active', true)
        ->orderByDesc('name')
        ->get();

    $subjects = Subject::where('is_active', true)
        ->with('classLevels')
        ->get(['id', 'name', 'school_section_id']);

    return view('admin.assessment-schemes.edit', compact(
        'assessmentScheme',
        'schoolSections',
        'academicSessions',
        'subjects'
    ));
}

public function update(Request $request, AssessmentScheme $assessmentScheme)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'total_marks' => ['required', 'numeric', 'gt:0', 'max:99999.99'],
        'components' => ['required', 'array', 'min:1'],
        'components.*.id' => ['nullable', 'integer'],
        'components.*.name' => ['required', 'string', 'max:255'],
        'components.*.component_type' => ['required', 'in:ca,exam'],
        'components.*.maximum_score' => ['required', 'numeric', 'gt:0', 'max:99999.99'],
    ]);

    if ($assessmentScheme->components()->whereHas('studentScores')->exists()) {
        return back()->withErrors([
            'components' => 'This scheme already has recorded student scores. Its components cannot be changed.',
        ])->withInput();
    }

    $examCount = collect($validated['components'])
        ->where('component_type', 'exam')
        ->count();

    if ($examCount !== 1) {
        throw ValidationException::withMessages([
            'components' => 'Each assessment scheme must contain exactly one examination component.',
        ]);
    }

    $componentTotal = collect($validated['components'])
        ->sum(fn ($component) => (float) $component['maximum_score']);

    if (abs($componentTotal - (float) $validated['total_marks']) > 0.001) {
        throw ValidationException::withMessages([
            'total_marks' => 'The component maximum marks must equal the total maximum marks.',
        ]);
    }

    DB::transaction(function () use ($validated, $assessmentScheme) {
        $assessmentScheme->update([
            'name' => $validated['name'],
            'total_marks' => $validated['total_marks'],
        ]);

        $submittedIds = [];

        foreach ($validated['components'] as $index => $component) {
            $componentModel = $assessmentScheme->components()
                ->find($component['id'] ?? null);

            if (!$componentModel) {
                $componentModel = $assessmentScheme->components()->make();
            }

            $componentModel->fill([
                'name' => $component['name'],
                'component_type' => $component['component_type'],
                'maximum_score' => $component['maximum_score'],
                'display_order' => $index + 1,
            ]);

            $assessmentScheme->components()->save($componentModel);
            $submittedIds[] = $componentModel->id;
        }

        $assessmentScheme->components()
            ->whereNotIn('id', $submittedIds)
            ->delete();
    });

    return redirect()
        ->route('assessment-schemes.index')
        ->with('success', 'Assessment scheme updated successfully.');
}
}
