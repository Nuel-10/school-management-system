
<x-admin-layout title="Results Approval">
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Results Approval
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Review, submit, approve and unapprove student results.
                </p>
            </div>

            <a href="{{ route('results.index') }}"
               class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                Enter Results
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="rounded-lg bg-red-100 p-4 text-red-800">
                <p class="font-semibold">Please correct the following:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Filters --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-800">
                Filter Results
            </h2>

            <form method="GET"
                  action="{{ route('results.approvals.index') }}"
                  class="grid gap-4 md:grid-cols-3">

                {{-- Academic Session --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Academic Session
                    </label>

                    <select name="academic_session_id"
                            class="w-full rounded-lg border-gray-300">
                        <option value="">All sessions</option>

                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}"
                                @selected(($filters['academic_session_id'] ?? '') == $session->id)>
                                {{ $session->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Class --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Class
                    </label>

                    <select name="class_arm_id"
                            class="w-full rounded-lg border-gray-300">
                        <option value="">All classes</option>

                        @foreach ($classArms as $arm)
                            <option value="{{ $arm->id }}"
                                @selected(($filters['class_arm_id'] ?? '') == $arm->id)>
                                {{ $arm->classLevel->name ?? 'Unknown Class' }} - {{ $arm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Subject --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Subject
                    </label>

                    <select name="subject_id"
                            class="w-full rounded-lg border-gray-300">
                        <option value="">All subjects</option>

                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                @selected(($filters['subject_id'] ?? '') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Term --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Term
                    </label>

                    <select name="term"
                            class="w-full rounded-lg border-gray-300">
                        <option value="">All terms</option>

                        @foreach (['First Term', 'Second Term', 'Third Term'] as $term)
                            <option value="{{ $term }}"
                                @selected(($filters['term'] ?? '') == $term)>
                                {{ $term }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Approval Status --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Approval Status
                    </label>

                    <select name="status"
                            class="w-full rounded-lg border-gray-300">
                        <option value="">All statuses</option>

                        <option value="draft"
                            @selected(($filters['status'] ?? '') == 'draft')>
                            Draft
                        </option>

                        <option value="submitted"
                            @selected(($filters['status'] ?? '') == 'submitted')>
                            Submitted
                        </option>

                        <option value="approved"
                            @selected(($filters['status'] ?? '') == 'approved')>
                            Approved
                        </option>
                    </select>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-blue-900 px-5 py-2.5 font-semibold text-white hover:bg-blue-800">
                        Apply Filters
                    </button>

                    <a href="{{ route('results.approvals.index') }}"
                       class="rounded-lg bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Status Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Draft Results</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $statusCounts['draft'] ?? 0 }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Awaiting submission</p>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
                <p class="text-sm font-medium text-blue-700">Submitted Results</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">
                    {{ $statusCounts['submitted'] ?? 0 }}
                </p>
                <p class="mt-1 text-xs text-blue-700">Awaiting approval</p>
            </div>

            <div class="rounded-xl border border-green-100 bg-green-50 p-5 shadow-sm">
                <p class="text-sm font-medium text-green-700">Approved Results</p>
                <p class="mt-2 text-3xl font-bold text-green-900">
                    {{ $statusCounts['approved'] ?? 0 }}
                </p>
                <p class="mt-1 text-xs text-green-700">Successfully approved</p>
            </div>

        </div>

        {{-- Determine Whether a Complete Group Is Selected --}}
        @php
            $groupSelected =
                !empty($filters['academic_session_id']) &&
                !empty($filters['class_arm_id']) &&
                !empty($filters['subject_id']) &&
                !empty($filters['term']);
        @endphp

        {{-- Bulk Approval Actions --}}
        @if ($groupSelected)
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-6">

                <h2 class="text-lg font-semibold text-gray-800">
                    Class-wide Approval Actions
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    These actions affect every eligible result matching the
                    selected session, class, subject and term.
                    The status filter does not restrict these group actions.
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-4">

                    {{-- Bulk Submit --}}
                    <form method="POST"
                          action="{{ route('results.approvals.bulk-submit') }}"
                          onsubmit="return confirm('Submit all draft results in this selected group?')">

                        @csrf

                        <input type="hidden" name="academic_session_id"
                               value="{{ $filters['academic_session_id'] }}">

                        <input type="hidden" name="class_arm_id"
                               value="{{ $filters['class_arm_id'] }}">

                        <input type="hidden" name="subject_id"
                               value="{{ $filters['subject_id'] }}">

                        <input type="hidden" name="term"
                               value="{{ $filters['term'] }}">

                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-bold text-white shadow-md transition hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Submit Class Results
                        </button>
                    </form>

                    {{-- Bulk Approve --}}
                    <form method="POST"
                          action="{{ route('results.approvals.bulk-approve') }}"
                          onsubmit="return confirm('Approve all submitted results in this selected group?')">

                        @csrf

                        <input type="hidden" name="academic_session_id"
                               value="{{ $filters['academic_session_id'] }}">

                        <input type="hidden" name="class_arm_id"
                               value="{{ $filters['class_arm_id'] }}">

                        <input type="hidden" name="subject_id"
                               value="{{ $filters['subject_id'] }}">

                        <input type="hidden" name="term"
                               value="{{ $filters['term'] }}">

                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-green-600 px-6 py-3 text-base font-bold text-white shadow-md transition hover:bg-green-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Approve Class Results
                        </button>
                    </form>

                    {{-- Bulk Unapprove --}}
                    <form method="POST"
                          action="{{ route('results.approvals.bulk-unapprove') }}"
                          onsubmit="return confirm('Unapprove all approved results in this selected group and return them to draft?')">

                        @csrf

                        <input type="hidden" name="academic_session_id"
                               value="{{ $filters['academic_session_id'] }}">

                        <input type="hidden" name="class_arm_id"
                               value="{{ $filters['class_arm_id'] }}">

                        <input type="hidden" name="subject_id"
                               value="{{ $filters['subject_id'] }}">

                        <input type="hidden" name="term"
                               value="{{ $filters['term'] }}">

                        <button type="submit"
        class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-base font-bold shadow-md transition hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        style="background-color: #ea580c !important; color: #ffffff !important;">
    Unapprove Class Results
</button>
                    </form>

                </div>
            </div>
        @endif

        {{-- Student Results Table --}}
        <div class="overflow-hidden rounded-xl bg-white shadow-sm">

            <div class="border-b border-gray-100 p-5">
                <h2 class="text-lg font-semibold text-gray-800">
                    Student Results
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Showing {{ $results->count() }}
                    of {{ $results->total() }} result(s).
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Admission No.</th>
                            <th class="px-4 py-3">Class</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Grade</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($results as $result)

                            @php
                                $resultStatus = strtolower(
                                    trim((string) $result->status)
                                );
                            @endphp

                            <tr class="hover:bg-gray-50">

                                {{-- Student Name --}}
                                <td class="px-4 py-4 font-medium text-gray-800">
                                    {{ $result->student->first_name ?? '' }}
                                    {{ $result->student->middle_name ?? '' }}
                                    {{ $result->student->last_name ?? '' }}
                                </td>

                                {{-- Admission Number --}}
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $result->student->admission_number ?? 'N/A' }}
                                </td>

                                {{-- Class --}}
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $result->classArm->classLevel->name ?? 'N/A' }}
                                    - {{ $result->classArm->name ?? '' }}
                                </td>

                                {{-- Subject --}}
                                <td class="px-4 py-4 text-gray-600">
                                    {{ $result->subject->name ?? 'N/A' }}
                                </td>

                                {{-- Total Score --}}
                                <td class="px-4 py-4 font-semibold text-gray-800">
                                    {{ number_format((float) $result->total_score, 2) }}
                                </td>

                                {{-- Grade --}}
                                <td class="px-4 py-4 font-semibold text-gray-800">
                                    {{ $result->grade ?? 'N/A' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4">

                                    @if ($resultStatus === 'approved')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            Approved
                                        </span>

                                    @elseif ($resultStatus === 'submitted')
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                            Submitted
                                        </span>

                                    @elseif ($resultStatus === 'draft')
                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                            Draft
                                        </span>

                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                            {{ $result->status }}
                                        </span>
                                    @endif

                                </td>

                                {{-- Individual Approval Actions --}}
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">

                                        {{-- Submit Draft Result --}}
                                        @if ($resultStatus === 'draft')
                                            <form method="POST"
                                                  action="{{ route('results.submit', ['studentResult' => $result->id]) }}">
                                                @csrf

                                                <button type="submit"
                                                        class="rounded-md bg-blue-700 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-800">
                                                    Submit
                                                </button>
                                            </form>

                                        {{-- Approve Submitted Result --}}
                                        @elseif ($resultStatus === 'submitted')
                                            <form method="POST"
                                                  action="{{ route('results.approve', ['studentResult' => $result->id]) }}"
                                                  onsubmit="return confirm('Approve this student result?')">
                                                @csrf

                                                <button type="submit"
                                                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                    Approve
                                                </button>
                                            </form>

                                        {{-- Unapprove Approved Result --}}
                                        @elseif ($resultStatus === 'approved')
                                            <form method="POST"
                                                  action="{{ route('results.unapprove', ['studentResult' => $result->id]) }}"
                                                  onsubmit="return confirm('Unapprove this result and return it to draft?')">
                                                @csrf

                                                <button type="submit"
                                                        class="rounded-md bg-orange-600 px-3 py-2 text-xs font-semibold text-white hover:bg-orange-700">
                                                    Unapprove
                                                </button>
                                            </form>

                                        @else
                                            <span class="text-xs text-red-600">
                                                Unknown status: {{ $result->status }}
                                            </span>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                            {{-- Approval History --}}
                            @if ($result->approvalHistories->isNotEmpty())
                                <tr class="bg-gray-50">
                                    <td colspan="8" class="px-4 py-3">
                                        <details>
                                            <summary class="cursor-pointer text-xs font-semibold text-blue-800">
                                                View approval history
                                            </summary>

                                            <div class="mt-3 space-y-2">
                                                @foreach ($result->approvalHistories as $history)
                                                    <div class="text-xs text-gray-600">

                                                        <strong class="capitalize">
                                                            {{ $history->action }}
                                                        </strong>

                                                        — {{ $history->previous_status }}
                                                        to {{ $history->new_status }}

                                                        by
                                                        {{ $history->user->name ?? 'Former user' }}

                                                        on
                                                        {{ $history->created_at->format('d M Y, h:i A') }}

                                                        @if ($history->remarks)
                                                            <p class="mt-1">
                                                                Remarks: {{ $history->remarks }}
                                                            </p>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="8"
                                    class="px-4 py-12 text-center text-gray-500">
                                    No student results found for the selected filters.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-gray-100 p-5">
                {{ $results->links() }}
            </div>

        </div>

    </div>
</x-admin-layout>
