<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\AssessmentScheme;
use App\Models\ClassArm;
use App\Models\GradeScale;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentResultController extends Controller
{
    public function index()
    {
        $sessions = AcademicSession::where('is_active', true)
            ->orderByDesc('name')
            ->get();

        $classArms = ClassArm::with('classLevel')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('is_active', true)
            ->with('classLevels')
            ->get(['id', 'name', 'school_section_id']);

        return view('admin.results.index', compact(
            'sessions',
            'classArms',
            'subjects'
        ));
    }

    public function students(Request $request)
    {
        $validated = $request->validate([
            'class_arm_id' => ['required', 'exists:class_arms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'term' => ['required', 'in:First Term,Second Term,Third Term'],
        ]);

        $classArm = ClassArm::with('classLevel')
            ->findOrFail($validated['class_arm_id']);

        $subject = $classArm->classLevel->subjects()
            ->where('subjects.id', $validated['subject_id'])
            ->where('subjects.is_active', true)
            ->first();

        if (!$subject) {
            return back()->withErrors([
                'subject_id' => 'This subject is not assigned to the selected class.',
            ])->withInput();
        }

        $scheme = AssessmentScheme::with('components')
            ->where('class_level_id', $classArm->class_level_id)
            ->where('subject_id', $subject->id)
            ->where('academic_session_id', $validated['academic_session_id'])
            ->where('is_active', true)
            ->first();

        if (!$scheme || $scheme->components->isEmpty()) {
            return back()->withErrors([
                'subject_id' => 'No active assessment scheme with components exists for this subject, class and academic session.',
            ])->withInput();
        }

        $students = Student::where('class_arm_id', $classArm->id)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $existingResults = StudentResult::with('componentScores')
            ->whereIn('student_id', $students->pluck('id'))
            ->where('subject_id', $subject->id)
            ->where('academic_session_id', $validated['academic_session_id'])
            ->where('term', $validated['term'])
            ->get()
            ->keyBy('student_id');

        $existingScores = [];

        foreach ($existingResults as $studentId => $result) {
            foreach ($result->componentScores as $componentScore) {
                $existingScores[$studentId][$componentScore->assessment_component_id]
                    = $componentScore->score;
            }
        }

        $session = AcademicSession::findOrFail(
            $validated['academic_session_id']
        );

        return view('admin.results.entry', [
            'students' => $students,
            'classArm' => $classArm,
            'subject' => $subject,
            'scheme' => $scheme,
            'session' => $session,
            'term' => $validated['term'],
            'existingScores' => $existingScores,
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'class_arm_id' => ['required', 'exists:class_arms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'term' => ['required', 'in:First Term,Second Term,Third Term'],
            'assessment_scheme_id' => ['required', 'exists:assessment_schemes,id'],
            'scores' => ['required', 'array'],
        ]);

        $classArm = ClassArm::with('classLevel')
            ->findOrFail($validated['class_arm_id']);

        $scheme = AssessmentScheme::with('components')
            ->whereKey($validated['assessment_scheme_id'])
            ->where('class_level_id', $classArm->class_level_id)
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_session_id', $validated['academic_session_id'])
            ->where('is_active', true)
            ->first();

        if (!$scheme || $scheme->components->isEmpty()) {
            throw ValidationException::withMessages([
                'assessment_scheme_id' => 'The selected assessment scheme is invalid or inactive.',
            ]);
        }

        $subjectAssigned = $classArm->classLevel->subjects()
            ->where('subjects.id', $validated['subject_id'])
            ->where('subjects.is_active', true)
            ->exists();

        if (!$subjectAssigned) {
            throw ValidationException::withMessages([
                'subject_id' => 'This subject is not assigned to the selected class.',
            ]);
        }

        $students = Student::where('class_arm_id', $classArm->id)
            ->where('status', 'active')
            ->get();

        $components = $scheme->components;

        $schemeMaximum = (float) $components->sum('maximum_score');

        if ($schemeMaximum <= 0) {
            throw ValidationException::withMessages([
                'assessment_scheme_id' => 'The assessment scheme must have a total maximum score greater than zero.',
            ]);
        }

        $rules = [];

        foreach ($students as $student) {
            foreach ($components as $component) {
                $rules["scores.{$student->id}.{$component->id}"] = [
                    'required',
                    'numeric',
                    'min:0',
                    'max:' . $component->maximum_score,
                ];
            }
        }

        $request->validate($rules);

        // Reject student IDs and component IDs outside this selection.
        foreach ($request->input('scores') as $studentId => $studentScores) {
            if (!$students->contains('id', (int) $studentId)) {
                throw ValidationException::withMessages([
                    'scores' => 'An invalid student was included in the submitted results.',
                ]);
            }

            if (!is_array($studentScores)) {
                throw ValidationException::withMessages([
                    'scores' => 'Invalid score information was submitted.',
                ]);
            }

            foreach (array_keys($studentScores) as $componentId) {
                if (!$components->contains('id', (int) $componentId)) {
                    throw ValidationException::withMessages([
                        'scores' => 'An invalid assessment component was submitted.',
                    ]);
                }
            }
        }

        DB::transaction(function () use (
            $request,
            $students,
            $components,
            $classArm,
            $scheme,
            $validated,
            $schemeMaximum
        ) {
            // Retrieve the active grading scale for this school section.
            $activeGradeScale = GradeScale::with('boundaries')
                ->where(
                    'school_section_id',
                    $classArm->classLevel->school_section_id
                )
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (!$activeGradeScale || $activeGradeScale->boundaries->isEmpty()) {
                throw ValidationException::withMessages([
                    'gradeScale' => 'No active grading scale with grade boundaries exists for this school section. Create one before saving results.',
                ]);
            }

            foreach ($students as $student) {
                $studentScores = $request->input(
                    "scores.{$student->id}",
                    []
                );

                $totalScore = 0;
                $caScore = 0;
                $examScore = 0;

                foreach ($components as $component) {
                    $score = (float) $studentScores[$component->id];

                    $totalScore += $score;

                    if ($component->component_type === 'exam') {
                        $examScore += $score;
                    } else {
                        $caScore += $score;
                    }
                }

                // Calculate the student's percentage using the actual
                // maximum marks configured for this assessment scheme.
                $percentage = round(
                    ($totalScore / $schemeMaximum) * 100,
                    2
                );

                // Find an existing result without overwriting its
                // original grading-scale reference.
                $result = StudentResult::where('student_id', $student->id)
                    ->where('subject_id', $validated['subject_id'])
                    ->where('academic_session_id', $validated['academic_session_id'])
                    ->where('term', $validated['term'])
                    ->lockForUpdate()
                    ->first();

                if ($result && $result->status === 'approved') {
                    throw ValidationException::withMessages([
                        'scores' => "The approved result for {$student->first_name} {$student->last_name} cannot be modified.",
                    ]);
                }

                // Existing results retain their original grading scale.
                // New results use the currently active grading scale.
                if ($result && $result->grade_scale_id) {
                    $gradeScale = GradeScale::with('boundaries')
                        ->whereKey($result->grade_scale_id)
                        ->lockForUpdate()
                        ->first();
                } else {
                    $gradeScale = $activeGradeScale;
                }

                if (!$gradeScale || $gradeScale->boundaries->isEmpty()) {
                    throw ValidationException::withMessages([
                        'gradeScale' => 'The grading scale linked to a result is unavailable or has no boundaries.',
                    ]);
                }

                $matchedBoundary = $gradeScale->boundaries->first(
                    function ($boundary) use ($percentage) {
                        return $percentage >= (float) $boundary->minimum_percentage
                            && $percentage <= (float) $boundary->maximum_percentage;
                    }
                );

                if (!$matchedBoundary) {
                    throw ValidationException::withMessages([
                        'gradeScale' => "No grade boundary covers {$percentage}%. Check the grading scale before saving results.",
                    ]);
                }

                if (!$result) {
                    $result = new StudentResult();
                }

                $result->fill([
                    'student_id' => $student->id,
                    'subject_id' => $validated['subject_id'],
                    'class_arm_id' => $classArm->id,
                    'academic_session_id' => $validated['academic_session_id'],
                    'term' => $validated['term'],
                    'ca_score' => $caScore,
                    'exam_score' => $examScore,
                    'total_score' => $totalScore,
                    'grade' => $matchedBoundary->grade,
                    'remark' => $matchedBoundary->remark,
                    'grade_scale_id' => $gradeScale->id,
                ]);

                $result->save();

                foreach ($components as $component) {
                    $score = (float) $studentScores[$component->id];

                    $result->componentScores()->updateOrCreate(
                        [
                            'assessment_component_id' => $component->id,
                        ],
                        [
                            'score' => $score,
                        ]
                    );
                }
            }
        });

        return redirect()
            ->route('results.index')
            ->with('success', 'Student results and grades have been saved successfully.');
    }
}
