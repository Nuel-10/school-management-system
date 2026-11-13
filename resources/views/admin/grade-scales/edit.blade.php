
<x-admin-layout title="Edit Grading Scale">
    <div class="mx-auto max-w-5xl space-y-6">

        <div>
            <a href="{{ route('grade-scales.index') }}"
               class="text-sm font-medium text-blue-800 hover:underline">
                ← Back to Grading System
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Edit Grading Scale
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Update the grading structure and performance remarks.
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
            <form method="POST"
                  action="{{ route('grade-scales.update', $gradeScale) }}"
                  id="grade-scale-form"
                  class="space-y-6">
                @csrf
                @method('PUT')

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
                                    @selected(old('school_section_id', $gradeScale->school_section_id) == $section->id)>
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
                               value="{{ old('name', $gradeScale->name) }}"
                               required
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">

                    <input type="checkbox" name="is_active" value="1"
                           id="is_active"
                           @checked(old('is_active', $gradeScale->is_active ? '1' : '0') == '1')
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
                                Edit existing grades or add new ones. Percentage ranges must not overlap.
                            </p>
                        </div>

                        <button type="button" id="add-grade"
                                class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                            + Add Grade
                        </button>
                    </div>

                    <div id="grades-container" class="space-y-3">
                        @php
                            $oldGrades = old('boundaries', $gradeScale->boundaries->toArray());
                        @endphp

                        @foreach ($oldGrades as $index => $grade)
                            <div class="grade-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12">
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Grade
                                    </label>

                                    <input type="text"
                                           name="boundaries[{{ $index }}][grade]"
                                           value="{{ $grade['grade'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Minimum (%)
                                    </label>

                                    <input type="number" step="0.01" min="0" max="100"
                                           name="boundaries[{{ $index }}][minimum_percentage]"
                                           value="{{ $grade['minimum_percentage'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Maximum (%)
                                    </label>

                                    <input type="number" step="0.01" min="0" max="100"
                                           name="boundaries[{{ $index }}][maximum_percentage]"
                                           value="{{ $grade['maximum_percentage'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Remark
                                    </label>

                                    <input type="text"
                                           name="boundaries[{{ $index }}][remark]"
                                           value="{{ $grade['remark'] ?? '' }}"
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
                    <p class="font-semibold text-gray-800">Grading information</p>
                    <p class="mt-1">
                        These percentage boundaries will determine the grades
                        assigned to student results, regardless of the assessment
                        scheme's total maximum marks.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                        Update Grading Scale
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
