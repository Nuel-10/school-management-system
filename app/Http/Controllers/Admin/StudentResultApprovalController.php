<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\ClassArm;
use App\Models\StudentResult;
use App\Models\StudentResultApprovalHistory;
use App\Models\Subject;
use App\Models\AssessmentScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class StudentResultApprovalController extends Controller
{
    /**
     * Display results and approval filters.
     */

public function index(Request $request)
{
    $filters = $request->validate([
        'academic_session_id' => ['nullable', 'integer', 'exists:academic_sessions,id'],
        'class_arm_id' => ['nullable', 'integer', 'exists:class_arms,id'],
        'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
        'term' => ['nullable', 'string', 'max:50'],
        'status' => ['nullable', 'in:draft,submitted,approved'],
    ]);

    $baseQuery = StudentResult::with([
        'student',
        'subject',
        'classArm.classLevel',
        'academicSession',
        'approvalHistories',
    ]);

    // Apply the academic group filters first.
    foreach ([
        'academic_session_id',
        'class_arm_id',
        'subject_id',
        'term',
    ] as $field) {
        if (!empty($filters[$field])) {
            $baseQuery->where($field, $filters[$field]);
        }
    }

    // Count statuses without restricting the counts by the status filter.
    $statusCounts = (clone $baseQuery)
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // Apply the selected status filter to the displayed results.
    $query = clone $baseQuery;

    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    $results = $query->latest()->paginate(50)->withQueryString();

    $sessions = AcademicSession::orderByDesc('id')->get();
    $classArms = ClassArm::with('classLevel')->get();
    $subjects = Subject::orderBy('name')->get();

    return view('admin.results.approvals', compact(
        'results',
        'sessions',
        'classArms',
        'subjects',
        'filters',
        'statusCounts'
    ));
}

    /**
     * Submit one student's draft result.
     */
    public function submit(Request $request, StudentResult $studentResult)
    {
        $this->validateRemarks($request);

        DB::transaction(function () use ($request, $studentResult) {
            $result = StudentResult::whereKey($studentResult->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertStatus($result, 'draft');
            $this->assertComplete($result);

            $this->changeStatus(
                $result,
                'submitted',
                'submitted',
                $request->input('remarks')
            );
        });

        return back()->with('success', 'Result submitted successfully.');
    }

    /**
     * Approve one student's submitted result.
     */
    public function approve(Request $request, StudentResult $studentResult)
    {
        $this->validateRemarks($request);

        DB::transaction(function () use ($request, $studentResult) {
            $result = StudentResult::whereKey($studentResult->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertStatus($result, 'submitted');
            $this->assertComplete($result);

            $this->changeStatus(
                $result,
                'approved',
                'approved',
                $request->input('remarks')
            );
        });

        return back()->with('success', 'Result approved successfully.');
    }

    /**
     * Unapprove one student's result and return it to draft.
     */
    public function unapprove(Request $request, StudentResult $studentResult)
    {
        $this->validateRemarks($request);

        DB::transaction(function () use ($request, $studentResult) {
            $result = StudentResult::whereKey($studentResult->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertStatus($result, 'approved');

            $this->changeStatus(
                $result,
                'draft',
                'unapproved',
                $request->input('remarks')
            );
        });

        return back()->with(
            'success',
            'Result unapproved and returned to draft.'
        );
    }

    /**
     * Submit all draft results in a selected class group.
     */
    public function bulkSubmit(Request $request)
    {
        $this->validateRemarks($request);
        $scope = $this->validateGroup($request);

        $count = DB::transaction(function () use ($request, $scope) {
            $results = $this->groupQuery($scope)
                ->where('status', 'draft')
                ->lockForUpdate()
                ->get();

            if ($results->isEmpty()) {
                throw ValidationException::withMessages([
                    'results' => 'There are no draft results to submit in this group.',
                ]);
            }

            foreach ($results as $result) {
                $this->assertComplete($result);
            }

            foreach ($results as $result) {
                $this->changeStatus(
                    $result,
                    'submitted',
                    'submitted',
                    $request->input('remarks')
                );
            }

            return $results->count();
        });

        return back()->with(
            'success',
            "{$count} student result(s) submitted successfully."
        );
    }

    /**
     * Approve all submitted results in a selected class group.
     */
    public function bulkApprove(Request $request)
    {
        $this->validateRemarks($request);
        $scope = $this->validateGroup($request);

        $count = DB::transaction(function () use ($request, $scope) {
            $results = $this->groupQuery($scope)
                ->where('status', 'submitted')
                ->lockForUpdate()
                ->get();

            if ($results->isEmpty()) {
                throw ValidationException::withMessages([
                    'results' => 'There are no submitted results awaiting approval in this group.',
                ]);
            }

            foreach ($results as $result) {
                $this->assertComplete($result);
            }

            foreach ($results as $result) {
                $this->changeStatus(
                    $result,
                    'approved',
                    'approved',
                    $request->input('remarks')
                );
            }

            return $results->count();
        });

        return back()->with(
            'success',
            "{$count} student result(s) approved successfully."
        );
    }

    /**
     * Unapprove all approved results in a selected class group.
     */
    public function bulkUnapprove(Request $request)
    {
        $this->validateRemarks($request);
        $scope = $this->validateGroup($request);

        $count = DB::transaction(function () use ($request, $scope) {
            $results = $this->groupQuery($scope)
                ->where('status', 'approved')
                ->lockForUpdate()
                ->get();

            if ($results->isEmpty()) {
                throw ValidationException::withMessages([
                    'results' => 'There are no approved results to unapprove in this group.',
                ]);
            }

            foreach ($results as $result) {
                $this->changeStatus(
                    $result,
                    'draft',
                    'unapproved',
                    $request->input('remarks')
                );
            }

            return $results->count();
        });

        return back()->with(
            'success',
            "{$count} student result(s) unapproved and returned to draft."
        );
    }

    /**
     * Validate the selected class group.
     */
    private function validateGroup(Request $request): array
    {
        return $request->validate([
            'academic_session_id' => [
                'required',
                'integer',
                'exists:academic_sessions,id',
            ],
            'term' => ['required', 'string', 'max:50'],
            'class_arm_id' => [
                'required',
                'integer',
                'exists:class_arms,id',
            ],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    /**
     * Restrict bulk actions to the exact selected group.
     */
    private function groupQuery(array $scope)
    {
        return StudentResult::where(
            'academic_session_id',
            $scope['academic_session_id']
        )
            ->where('term', $scope['term'])
            ->where('class_arm_id', $scope['class_arm_id'])
            ->where('subject_id', $scope['subject_id']);
    }

    /**
     * Ensure the result is in the expected state.
     */
    private function assertStatus(StudentResult $result, string $expected): void
    {
        if ($result->status !== $expected) {
            throw ValidationException::withMessages([
                'status' => "This action requires a {$expected} result. Current status: {$result->status}.",
            ]);
        }
    }

    /**
     * Prevent empty or unsaved results from being submitted or approved.
     */

private function assertComplete(StudentResult $result): void
{
    // Retrieve the class arm and its class level.
    $classArm = $result->classArm()
        ->with('classLevel')
        ->first();

    if (!$classArm || !$classArm->classLevel) {
        throw ValidationException::withMessages([
            'results' => "Result ID {$result->id} has no valid class assignment.",
        ]);
    }

    // Find the active scheme used for this class, subject and session.
    $scheme = AssessmentScheme::with('components')
        ->where('class_level_id', $classArm->class_level_id)
        ->where('subject_id', $result->subject_id)
        ->where('academic_session_id', $result->academic_session_id)
        ->where('is_active', true)
        ->first();

    if (!$scheme || $scheme->components->isEmpty()) {
        throw ValidationException::withMessages([
            'results' => "No active assessment scheme exists for result ID {$result->id}.",
        ]);
    }

    $components = $scheme->components;
    $scores = $result->componentScores()->get();

    if ($scores->count() !== $components->count()) {
        throw ValidationException::withMessages([
            'results' => "Result ID {$result->id} is incomplete. Every assessment component must have exactly one saved score.",
        ]);
    }

    foreach ($components as $component) {
        $matchingScores = $scores->where(
            'assessment_component_id',
            $component->id
        );

        if ($matchingScores->count() !== 1) {
            throw ValidationException::withMessages([
                'results' => "Result ID {$result->id} is missing a valid score for {$component->name}.",
            ]);
        }

        $score = $matchingScores->first()->score;
        $maximum = (float) $component->maximum_score;

        if (
            !is_numeric($score) ||
            (float) $score < 0 ||
            (float) $score > $maximum
        ) {
            throw ValidationException::withMessages([
                'results' => "The score for {$component->name} in result ID {$result->id} is invalid.",
            ]);
        }
    }
}

    /**
     * Change status and create an audit history record.
     */
    private function changeStatus(
        StudentResult $result,
        string $newStatus,
        string $action,
        ?string $remarks = null
    ): void {
        $previousStatus = $result->status;

        $result->status = $newStatus;
        $result->save();

        StudentResultApprovalHistory::create([
            'student_result_id' => $result->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'remarks' => $remarks,
        ]);
    }

    /**
     * Validate optional action remarks.
     */
    private function validateRemarks(Request $request): void
    {
        $request->validate([
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
