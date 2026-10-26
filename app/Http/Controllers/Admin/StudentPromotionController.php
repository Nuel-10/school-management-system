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
                'required',
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
            (int) $sourceArm->classLevel->school_section_id
            !== (int) $validated['school_section_id']
        ) {
            throw ValidationException::withMessages([
                'from_class_arm_id' =>
                    'The selected source class does not belong to this school section.',
            ]);
        }

        $destinationArm = null;

        if ($validated['decision'] !== 'repeated') {
            $destinationArm = ClassArm::with('classLevel')
                ->where('is_active', true)
                ->findOrFail($validated['to_class_arm_id']);

            if (
                (int) $destinationArm->classLevel->school_section_id
                !== (int) $validated['school_section_id']
            ) {
                throw ValidationException::withMessages([
                    'to_class_arm_id' =>
                        'The destination class must belong to the selected school section.',
                ]);
            }

            if ($sourceArm->id === $destinationArm->id) {
                throw ValidationException::withMessages([
                    'to_class_arm_id' =>
                        'Choose a different destination class.',
                ]);
            }
        } else {
            // A repeating student stays in the same class.
            $validated['to_class_arm_id'] = $sourceArm->id;
        }

        DB::transaction(function () use ($validated, $request) {
            $students = Student::whereIn('id', $validated['student_ids'])
                ->lockForUpdate()
                ->get();

            if ($students->count() !== count($validated['student_ids'])) {
                throw ValidationException::withMessages([
                    'student_ids' =>
                        'One or more selected students could not be found.',
                ]);
            }

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
                            'A selected student is no longer active in the chosen source class. Reload the list and try again.',
                    ]);
                }
            }

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
            ->with('success', 'Student promotion records saved successfully.');
    }
}
