
<x-admin-layout title="Enter Student Results">
    <div class="mx-auto max-w-full space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Enter Student Results</h1>
            <p class="mt-1 text-sm text-gray-500">
                Enter each student's scores using the configured assessment structure.
            </p>
        </div>

        <div class="grid gap-4 rounded-xl bg-white p-5 shadow-sm md:grid-cols-4">
            <div>
                <p class="text-xs text-gray-500">Class</p>
                <p class="font-semibold text-gray-800">
                    {{ $classArm->classLevel->name }} - {{ $classArm->name }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Subject</p>
                <p class="font-semibold text-gray-800">{{ $subject->name }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Academic Session</p>
                <p class="font-semibold text-gray-800">{{ $session->name }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Term</p>
                <p class="font-semibold text-gray-800">{{ $term }}</p>
            </div>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Assessment Components
                    </h2>
                    <p class="text-sm text-gray-500">{{ $scheme->name }}</p>
                </div>

                <div class="rounded-lg bg-blue-50 px-4 py-2">
                    <span class="text-sm text-blue-800">Total Maximum Score:</span>
                    <strong class="ml-1 text-blue-900">
                        {{ number_format($scheme->components->sum('maximum_score'), 2) }}
                    </strong>
                </div>
            </div>

            @if ($students->isEmpty())
                <div class="rounded-lg bg-yellow-50 p-5 text-yellow-800">
                    No active students were found in this class.
                </div>
            @else
                <form method="POST" action="{{ route('results.save') }}">
                    @csrf

                    <input type="hidden" name="class_arm_id" value="{{ $classArm->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="hidden" name="academic_session_id" value="{{ $session->id }}">
                    <input type="hidden" name="term" value="{{ $term }}">
                    <input type="hidden" name="assessment_scheme_id" value="{{ $scheme->id }}">

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="sticky left-0 bg-gray-50 px-4 py-3 text-left">
                                        Student
                                    </th>

                                    <th class="px-4 py-3 text-left">Admission No.</th>

                                    @foreach ($scheme->components as $component)
                                        <th class="min-w-32 px-4 py-3 text-center">
                                            {{ $component->name }}
                                            <span class="block text-xs font-normal text-gray-500">
                                                Max: {{ number_format($component->maximum_score, 2) }}
                                            </span>
                                        </th>
                                    @endforeach

                                    <th class="px-4 py-3 text-center">Total</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach ($students as $student)
                                    <tr>
                                        <td class="sticky left-0 bg-white px-4 py-3 font-medium text-gray-800">
                                            {{ $student->last_name }},
                                            {{ $student->first_name }}
                                            {{ $student->middle_name }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                            {{ $student->admission_number }}
                                        </td>

                                        @foreach ($scheme->components as $component)
                                            <td class="px-3 py-3">
                                                <input
                                                    type="number"
                                                    name="scores[{{ $student->id }}][{{ $component->id }}]"
                                                    min="0"
                                                    max="{{ $component->maximum_score }}"
                                                    step="0.01"
                                                    data-student="{{ $student->id }}"
                                                    class="component-score w-24 rounded-lg border-gray-300 text-center"
                                                    placeholder="0"
                                                    value="{{ old('scores.'.$student->id.'.'.$component->id, $existingScores[$student->id][$component->id] ?? '') }}"
                                                    required
                                                >
                                            </td>
                                        @endforeach

                                        <td class="px-4 py-3 text-center font-bold text-blue-900">
                                            <span id="total-{{ $student->id }}">0.00</span>
                                            <span class="text-xs font-normal text-gray-500">
                                                / {{ number_format($scheme->components->sum('maximum_score'), 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t pt-5">
                        <p class="mb-4 text-sm text-gray-500">
                            Enter scores within the maximum marks shown for each component.
                        </p>

                        <button
                            type="submit"
                            class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800"
                        >
                            Save Student Results
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scoreInputs = document.querySelectorAll('.component-score');

            function calculateTotal(studentId) {
                let total = 0;

                document.querySelectorAll(
                    '.component-score[data-student="' + studentId + '"]'
                ).forEach(function (input) {
                    total += Number(input.value) || 0;
                });

                const totalElement = document.getElementById('total-' + studentId);

                if (totalElement) {
                    totalElement.textContent = total.toFixed(2);
                }
            }

            scoreInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    calculateTotal(this.dataset.student);
                });

                calculateTotal(input.dataset.student);
            });
        });
    </script>
</x-admin-layout>
