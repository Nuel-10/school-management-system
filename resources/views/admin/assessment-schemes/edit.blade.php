
<x-admin-layout title="Edit Assessment Scheme">
    <div class="mx-auto max-w-5xl space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Assessment Scheme</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Update the assessment components and maximum marks.
                </p>
            </div>

            <a href="{{ route('assessment-schemes.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Back to Assessment Management
            </a>
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
            <div class="mb-6 rounded-lg bg-gray-50 p-4">
                <p class="text-sm text-gray-600">School section</p>
                <p class="font-semibold text-gray-800">{{ $assessmentScheme->schoolSection->name }}</p>

                <p class="mt-3 text-sm text-gray-600">Class</p>
                <p class="font-semibold text-gray-800">{{ $assessmentScheme->classLevel->name }}</p>

                <p class="mt-3 text-sm text-gray-600">Subject</p>
                <p class="font-semibold text-gray-800">{{ $assessmentScheme->subject->name }}</p>

                <p class="mt-3 text-sm text-gray-600">Academic session</p>
                <p class="font-semibold text-gray-800">{{ $assessmentScheme->academicSession->name }}</p>
            </div>

            <form method="POST"
                  action="{{ route('assessment-schemes.update', $assessmentScheme) }}"
                  id="edit-assessment-form"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Scheme Name
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $assessmentScheme->name) }}"
                               required
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Total Maximum Marks
                        </label>
                        <input type="number"
                               name="total_marks"
                               id="total_marks"
                               value="{{ old('total_marks', $assessmentScheme->total_marks) }}"
                               min="0.01"
                               step="0.01"
                               required
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="border-t pt-5">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="font-semibold text-gray-800">Assessment Components</h2>
                            <p class="text-sm text-gray-500">
                                Edit existing components or add more CA components.
                            </p>
                        </div>

                        <button type="button"
                                id="add-ca"
                                class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                            + Add CA Component
                        </button>
                    </div>

                    <div id="components-container" class="space-y-3">
                        @php
                            $oldComponents = old('components');

                            if ($oldComponents === null) {
                                $oldComponents = $assessmentScheme->components->map(function ($component) {
                                    return [
                                        'id' => $component->id,
                                        'name' => $component->name,
                                        'component_type' => $component->component_type,
                                        'maximum_score' => $component->maximum_score,
                                    ];
                                })->toArray();
                            }
                        @endphp

                        @foreach ($oldComponents as $index => $component)
                            <div class="component-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12">
                                <input type="hidden"
                                       name="components[{{ $index }}][id]"
                                       value="{{ $component['id'] ?? '' }}">

                                <div class="md:col-span-5">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Component Name
                                    </label>
                                    <input type="text"
                                           name="components[{{ $index }}][name]"
                                           value="{{ $component['name'] ?? '' }}"
                                           required
                                           class="w-full rounded-lg border-gray-300">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Component Type
                                    </label>
                                    <select name="components[{{ $index }}][component_type]"
                                            class="component-type w-full rounded-lg border-gray-300">
                                        <option value="ca" @selected(($component['component_type'] ?? '') === 'ca')>
                                            Continuous Assessment
                                        </option>
                                        <option value="exam" @selected(($component['component_type'] ?? '') === 'exam')>
                                            Examination
                                        </option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">
                                        Maximum Marks
                                    </label>
                                    <input type="number"
                                           name="components[{{ $index }}][maximum_score]"
                                           value="{{ $component['maximum_score'] ?? '' }}"
                                           min="0.01"
                                           step="0.01"
                                           required
                                           class="maximum-score w-full rounded-lg border-gray-300">
                                </div>

                                <div class="flex items-end md:col-span-2">
                                    <button type="button"
                                            class="remove-component w-full rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 rounded-lg bg-gray-50 p-4">
                        <div class="flex flex-wrap justify-between gap-3">
                            <span class="font-medium text-gray-700">
                                Sum of component maximum marks
                            </span>
                            <span class="font-bold text-gray-900" id="component-total">
                                0.00
                            </span>
                        </div>
                        <p id="total-message" class="mt-2 text-sm text-gray-500">
                            Component marks should equal the total maximum marks.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 border-t pt-5">
                    <button type="submit"
                            class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                        Save Changes
                    </button>

                    <a href="{{ route('assessment-schemes.index') }}"
                       class="rounded-lg border border-gray-300 px-6 py-3 font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('components-container');
            const totalInput = document.getElementById('total_marks');
            const addButton = document.getElementById('add-ca');

            let nextIndex = {{ count($oldComponents) }};

            function updateTotal() {
                let total = 0;

                container.querySelectorAll('.maximum-score').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });

                const configuredTotal = parseFloat(totalInput.value) || 0;

                document.getElementById('component-total').textContent = total.toFixed(2);

                const message = document.getElementById('total-message');

                if (Math.abs(total - configuredTotal) < 0.001) {
                    message.textContent = 'Component marks match the configured total.';
                    message.className = 'mt-2 text-sm text-green-700';
                } else {
                    message.textContent = 'Component marks must equal the configured total.';
                    message.className = 'mt-2 text-sm text-red-600';
                }
            }

            function bindRow(row) {
                row.querySelector('.maximum-score').addEventListener('input', updateTotal);

                row.querySelector('.remove-component').addEventListener('click', function () {
                    if (container.querySelectorAll('.component-row').length <= 1) {
                        alert('At least one component is required.');
                        return;
                    }

                    row.remove();
                    updateTotal();
                });
            }

            container.querySelectorAll('.component-row').forEach(bindRow);

            addButton.addEventListener('click', function () {
                const row = document.createElement('div');

                row.className = 'component-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12';

                row.innerHTML = `
                    <input type="hidden" name="components[${nextIndex}][id]" value="">

                    <div class="md:col-span-5">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Component Name</label>
                        <input type="text" name="components[${nextIndex}][name]" required
                               placeholder="e.g. Test 3" class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Component Type</label>
                        <select name="components[${nextIndex}][component_type]"
                                class="component-type w-full rounded-lg border-gray-300">
                            <option value="ca">Continuous Assessment</option>
                            <option value="exam">Examination</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Maximum Marks</label>
                        <input type="number" name="components[${nextIndex}][maximum_score]"
                               min="0.01" step="0.01" required
                               class="maximum-score w-full rounded-lg border-gray-300">
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="button"
                                class="remove-component w-full rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600">
                            Remove
                        </button>
                    </div>
                `;

                container.appendChild(row);
                bindRow(row);
                nextIndex++;
                updateTotal();
            });

            totalInput.addEventListener('input', updateTotal);
            updateTotal();
        });
    </script>
</x-admin-layout>
