
<x-admin-layout title="Create Grading Scale">
    <div class="mx-auto max-w-5xl space-y-6">

        <div>
            <a href="{{ route('grade-scales.index') }}"
               class="text-sm font-medium text-blue-800 hover:underline">
                ← Back to Grading System
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Create Grading Scale
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Define the percentage ranges and remarks used to grade student results.
            </p>
        </div>

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

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('grade-scales.store') }}"
                  id="grade-scale-form" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            School Section
                        </label>

                        <select name="school_section_id" required
                                class="w-full rounded-lg border-gray-300">
                            <option value="">Select school section</option>

                            @foreach ($schoolSections as $section)
                                <option value="{{ $section->id }}"
                                    @selected(old('school_section_id') == $section->id)>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Grading Scale Name
                        </label>

                        <input type="text" name="name"
                               value="{{ old('name') }}"
                               placeholder="e.g. Standard College Grading"
                               required
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           id="is_active"
                           @checked(old('is_active', '1') == '1')
                           class="rounded border-gray-300 text-blue-900">
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Set this grading scale as active
                    </label>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Grade Boundaries
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Percentage ranges must not overlap.
                            </p>
                        </div>

                        <button type="button" id="add-grade"
                                class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                            + Add Grade
                        </button>
                    </div>

                    <div id="grades-container" class="space-y-3">
                        @php
                            $oldGrades = old('boundaries', [
                                ['grade' => 'A', 'minimum_percentage' => 70, 'maximum_percentage' => 100, 'remark' => 'Excellent'],
                                ['grade' => 'B', 'minimum_percentage' => 60, 'maximum_percentage' => 69.99, 'remark' => 'Very Good'],
                                ['grade' => 'C', 'minimum_percentage' => 50, 'maximum_percentage' => 59.99, 'remark' => 'Good'],
                                ['grade' => 'D', 'minimum_percentage' => 45, 'maximum_percentage' => 49.99, 'remark' => 'Pass'],
                                ['grade' => 'F', 'minimum_percentage' => 0, 'maximum_percentage' => 44.99, 'remark' => 'Fail'],
                            ]);
                        @endphp

                        @foreach ($oldGrades as $index => $grade)
                            <div class="grade-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12">
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Grade</label>
                                    <input type="text"
                                           name="boundaries[{{ $index }}][grade]"
                                           value="{{ $grade['grade'] ?? '' }}"
                                           placeholder="A"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Minimum (%)</label>
                                    <input type="number" step="0.01" min="0" max="100"
                                           name="boundaries[{{ $index }}][minimum_percentage]"
                                           value="{{ $grade['minimum_percentage'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Maximum (%)</label>
                                    <input type="number" step="0.01" min="0" max="100"
                                           name="boundaries[{{ $index }}][maximum_percentage]"
                                           value="{{ $grade['maximum_percentage'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Remark</label>
                                    <input type="text"
                                           name="boundaries[{{ $index }}][remark]"
                                           value="{{ $grade['remark'] ?? '' }}"
                                           placeholder="Excellent"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="flex items-end md:col-span-1">
                                    <button type="button"
                                            class="remove-grade w-full rounded-lg border border-red-200 px-2 py-2 text-sm text-red-600 hover:bg-red-50">
                                        ×
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
                    <p class="font-semibold text-gray-800">Important</p>
                    <p class="mt-1">
                        Grades are calculated using percentages, not raw marks.
                        This allows the same grading scale to work with different
                        assessment totals, such as 50, 100 or 150 marks.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                        Save Grading Scale
                    </button>

                    <a href="{{ route('grade-scales.index') }}"
                       class="rounded-lg border border-gray-300 px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('grades-container');
            const addButton = document.getElementById('add-grade');

            let nextIndex = {{ count($oldGrades) }};

            function bindRemoveButton(row) {
                row.querySelector('.remove-grade').addEventListener('click', function () {
                    if (container.querySelectorAll('.grade-row').length <= 1) {
                        alert('At least one grade is required.');
                        return;
                    }

                    row.remove();
                });
            }

            container.querySelectorAll('.grade-row').forEach(bindRemoveButton);

            addButton.addEventListener('click', function () {
                const row = document.createElement('div');

                row.className = 'grade-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12';

                row.innerHTML = `
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Grade</label>
                        <input type="text" name="boundaries[${nextIndex}][grade]"
                               placeholder="E" required
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Minimum (%)</label>
                        <input type="number" step="0.01" min="0" max="100"
                               name="boundaries[${nextIndex}][minimum_percentage]"
                               required class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Maximum (%)</label>
                        <input type="number" step="0.01" min="0" max="100"
                               name="boundaries[${nextIndex}][maximum_percentage]"
                               required class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Remark</label>
                        <input type="text" name="boundaries[${nextIndex}][remark]"
                               placeholder="Remark" required
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="flex items-end md:col-span-1">
                        <button type="button"
                                class="remove-grade w-full rounded-lg border border-red-200 px-2 py-2 text-sm text-red-600 hover:bg-red-50">
                            ×
                        </button>
                    </div>
                `;

                container.appendChild(row);
                bindRemoveButton(row);
                nextIndex++;
            });
        });
    </script>
</x-admin-layout>
