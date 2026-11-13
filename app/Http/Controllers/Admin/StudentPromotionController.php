<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentPromotion;
use App\Models\ClassArm;
use App\Models\SchoolSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentPromotionController extends Controller
{
    /**
     * Display the student promotion page.
     */
    public function index(Request $request)
    {
        $schoolSections = SchoolSection::where('is_active', true)
            ->orderBy('name')
            ->get();

        $classArms = ClassArm::with('classLevel.schoolSection')
            ->where('is_active', true)
            ->whereHas('classLevel', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get();

        $students = collect();

        if ($request->filled('class_arm_id')) {
            $request->validate([
                'class_arm_id' => [
                    'required',
                    'integer',
                    'exists:class_arms,id',
                ],
                'school_section_id' => [
                    'required',
                    'integer',
                    'exists:school_sections,id',
                ],
            ]);

            $sourceArm = ClassArm::with('classLevel')
                ->where('is_active', true)
                ->findOrFail($request->integer('class_arm_id'));

            if (
                (int) $sourceArm->classLevel->school_section_id
                !== $request->integer('school_section_id')
            ) {
                throw ValidationException::withMessages([
                    'class_arm_id' =>
                        'The selected class does not belong to the selected school section.',
                ]);
            }

            $students = Student::with('classArm.classLevel')
                ->where('school_section_id', $request->integer('school_section_id'))
                ->where('class_arm_id', $sourceArm->id)
                ->where('status', 'active')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        return view('admin.promotions.index', compact(
            'schoolSections',
            'classArms',
            'students'
        ));
    }

    /**
     * Process promotion, repetition, or transfer decisions.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_section_id' => [
                'required',
                'integer',
                'exists:school_sections,id',
            ],
            'from_class_arm_id' => [
                'required',
                'integer',
                'exists:class_arms,id',
            ],
            'to_class_arm_id' => [
                Rule::requiredIf(
                    $request->input('decision') !== 'repeated'
                ),
                'nullable',
                'integer',
                'exists:class_arms,id',
            ],
            'academic_session' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}-\d{4}$/',
            ],
            'term' => [
                'nullable',
                'string',
                'max:30',
            ],
            'decision' => [
                'required',
                Rule::in(['promoted', 'repeated', 'transferred']),
            ],
            'student_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'student_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:students,id',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $sourceArm = ClassArm::with('classLevel')
            ->where('is_active', true)
            ->findOrFail($validated['from_class_arm_id']);

        if (
            !$sourceArm->classLevel
            || !$sourceArm->classLevel->is_active
            || (int) $sourceArm->classLevel->school_section_id
                !== (int) $validated['school_section_id']
        ) {
            throw ValidationException::withMessages([
                'from_class_arm_id' =>
                    'The source class must be active and belong to the selected school section.',
            ]);
        }

        if ($validated['decision'] === 'repeated') {
            // Repeating students remain in their current class.
            $validated['to_class_arm_id'] = $sourceArm->id;
        } else {
            $destinationArm = ClassArm::with('classLevel')
                ->where('is_active', true)
                ->findOrFail($validated['to_class_arm_id']);

            if (
                !$destinationArm->classLevel
                || !$destinationArm->classLevel->is_active
                || (int) $destinationArm->classLevel->school_section_id
                    !== (int) $validated['school_section_id']
            ) {
                throw ValidationException::withMessages([
                    'to_class_arm_id' =>
                        'The destination class must be active and belong to the selected school section.',
                ]);
            }

            if ((int) $sourceArm->id === (int) $destinationArm->id) {
                throw ValidationException::withMessages([
                    'to_class_arm_id' =>
                        'Choose a different destination class.',
                ]);
            }
        }

        DB::transaction(function () use ($validated, $request) {
            $studentIds = $validated['student_ids'];

            $students = Student::whereIn('id', $studentIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($students->count() !== count($studentIds)) {
                throw ValidationException::withMessages([
                    'student_ids' =>
                        'One or more selected students could not be found.',
                ]);
            }

            // Confirm that every student still belongs to the selected
            // source class before recording or changing anything.
            foreach ($students as $student) {
                if (
                    (int) $student->school_section_id
                        !== (int) $validated['school_section_id']
                    || (int) $student->class_arm_id
                        !== (int) $validated['from_class_arm_id']
                    || $student->status !== 'active'
                ) {
                    throw ValidationException::withMessages([
                        'student_ids' =>
                            'A selected student is no longer active in the chosen source class. Reload the student list and try again.',
                    ]);
                }
            }

            // Prevent a second decision for the same student in the
            // same academic session, regardless of the term.
            $existingPromotion = StudentPromotion::whereIn(
                    'student_id',
                    $studentIds
                )
                ->where(
                    'academic_session',
                    $validated['academic_session']
                )
                ->exists();

            if ($existingPromotion) {
                throw ValidationException::withMessages([
                    'student_ids' =>
                        'At least one selected student already has a decision recorded for this academic session. No changes were saved. Check the promotion history before continuing.',
                ]);
            }

            // All checks have passed. Record each decision and then
            // update class placement when required.
            foreach ($students as $student) {
                StudentPromotion::create([
                    'student_id' => $student->id,
                    'from_class_arm_id' => $student->class_arm_id,
                    'to_class_arm_id' => $validated['to_class_arm_id'],
                    'academic_session' => $validated['academic_session'],
                    'term' => $validated['term'] ?? null,
                    'decision' => $validated['decision'],
                    'remarks' => $validated['remarks'] ?? null,
                    'processed_by' => $request->user()?->id,
                ]);

                if ($validated['decision'] !== 'repeated') {
                    $student->update([
                        'class_arm_id' => $validated['to_class_arm_id'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('promotions.index')
            ->with(
                'success',
                'Student promotion decisions saved successfully.'
            );
    }
}
