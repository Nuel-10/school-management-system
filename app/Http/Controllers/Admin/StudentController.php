<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolSection;
use App\Models\ClassArm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with([
            'schoolSection',
            'classArm.classLevel',
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('admission_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('parent_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('school_section_id'), function ($query) use ($request) {
                $query->where('school_section_id', $request->input('school_section_id'));
            })
            ->when($request->filled('class_arm_id'), function ($query) use ($request) {
                $query->where('class_arm_id', $request->input('class_arm_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'schoolSections' => SchoolSection::orderBy('name')->get(),
            'classArms' => ClassArm::with('classLevel.schoolSection')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create()
    {
        return view('admin.students.create', [
            'schoolSections' => SchoolSection::where('is_active', true)
                ->orderBy('name')
                ->get(),
            'classArms' => ClassArm::with('classLevel.schoolSection')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateStudent($request);

        // Generate the admission number on the server.
        $validated['admission_number'] = $this->generateAdmissionNumber();

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student registered successfully.');
    }

    public function show(Student $student)
    {
        $student->load([
            'schoolSection',
            'classArm.classLevel',
        ]);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', [
            'student' => $student,
            'schoolSections' => SchoolSection::where('is_active', true)
                ->orderBy('name')
                ->get(),
            'classArms' => ClassArm::with('classLevel.schoolSection')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $this->validateStudent($request, $student);

        // Existing admission numbers must not be replaced during editing.
        unset($validated['admission_number']);

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student record deleted successfully.');
    }

    private function generateAdmissionNumber(): string
    {
        $prefix = 'ROC/' . date('Y') . '/';

        // Find the highest existing number for this year's prefix.
        $existingNumbers = Student::where(
            'admission_number',
            'like',
            $prefix . '%'
        )->pluck('admission_number');

        $highestNumber = 0;

        foreach ($existingNumbers as $number) {
            $suffix = substr($number, strlen($prefix));

            if (ctype_digit($suffix)) {
                $highestNumber = max($highestNumber, (int) $suffix);
            }
        }

        // Skip any number that already exists.
        do {
            $highestNumber++;
            $admissionNumber = $prefix . str_pad(
                (string) $highestNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
        } while (
            Student::where('admission_number', $admissionNumber)->exists()
        );

        return $admissionNumber;
    }

    private function validateStudent(
        Request $request,
        ?Student $student = null
    ): array {
        $sectionId = $request->input('school_section_id');

        return $request->validate([
            'admission_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('students', 'admission_number')
                    ->ignore($student?->id),
            ],

            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],

            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],

            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'parent_email' => ['nullable', 'email', 'max:255'],

            'school_section_id' => [
                'required',
                'exists:school_sections,id',
            ],

            'class_arm_id' => [
                'nullable',
                Rule::exists('class_arms', 'id')->where(function ($query) use ($sectionId) {
                    $query->where('is_active', true)
                        ->whereIn('class_level_id', function ($subquery) use ($sectionId) {
                            $subquery->select('id')
                                ->from('class_levels')
                                ->where('school_section_id', $sectionId)
                                ->where('is_active', true);
                        });
                }),
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive', 'graduated', 'transferred']),
            ],
        ]);
    }
}
