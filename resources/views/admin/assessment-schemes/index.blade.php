
<x-admin-layout title="Assessment Management">
    <div class="mx-auto max-w-7xl space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Assessment Management</h1>
            <p class="mt-1 text-sm text-gray-500">
                Configure CA components, examination marks and total assessment scores.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

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
            <h2 class="mb-5 text-lg font-semibold text-gray-800">
                Create Assessment Scheme
            </h2>

            <form method="POST" action="{{ route('assessment-schemes.store') }}"
                class="space-y-6" id="assessment-form">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            School Section
                        </label>
                        <select name="school_section_id" id="school_section_id" required
                            class="w-full rounded-lg border-gray-300">
                            <option value="">Select section</option>
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
                            Class Level
                        </label>
                        <select name="class_level_id" id="class_level_id" required
                            class="w-full rounded-lg border-gray-300">
                            <option value="">Select class</option>
                            @foreach ($schoolSections as $section)
                                @foreach ($section->classLevels as $level)
                                    <option value="{{ $level->id }}"
                                        data-section="{{ $section->id }}"
                                        @selected(old('class_level_id') == $level->id)>
                                        {{ $section->name }} - {{ $level->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Subject
                        </label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full rounded-lg border-gray-300">
                            <option value="">Select subject</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Academic Session
                        </label>
                        <select name="academic_session_id" required
                            class="w-full rounded-lg border-gray-300">
                            <option value="">Select session</option>
                            @foreach ($academicSessions as $session)
                                <option value="{{ $session->id }}"
                                    @selected(old('academic_session_id') == $session->id)>
                                    {{ $session->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Scheme Name
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="e.g. Standard Assessment"
                            required class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Total Maximum Marks
                        </label>
                        <input type="number" name="total_marks" id="total_marks"
                            value="{{ old('total_marks', 100) }}" min="0.01"
                            step="0.01" required
                            class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="border-t pt-5">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">Assessment Components</h3>
                            <p class="text-sm text-gray-500">
                                Add as many CA components as your school requires, plus one examination.
                            </p>
                        </div>

                        <button type="button" id="add-ca"
                            class="rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                            + Add CA Component
                        </button>
                    </div>

                    <div id="components-container" class="space-y-3">
                        @php
                            $oldComponents = old('components', [
                                ['name' => 'Assignment', 'component_type' => 'ca', 'maximum_score' => 10],
                                ['name' => 'Examination', 'component_type' => 'exam', 'maximum_score' => 90],
                            ]);
                        @endphp

                        @foreach ($oldComponents as $index => $component)
                            <div class="component-row grid gap-3 rounded-lg border border-gray-200 p-4 md:grid-cols-12">
                                <div class="md:col-span-5">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Component Name</label>
                                    <input type="text" name="components[{{ $index }}][name]"
                                        value="{{ $component['name'] ?? '' }}" required
                                        class="w-full rounded-lg border-gray-300"
                                        placeholder="e.g. Assignment">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Component Type</label>
                                    <select name="components[{{ $index }}][component_type]"
                                        class="component-type w-full rounded-lg border-gray-300">
                                        <option value="ca" @selected(($component['component_type'] ?? '') === 'ca')>Continuous Assessment</option>
                                        <option value="exam" @selected(($component['component_type'] ?? '') === 'exam')>Examination</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Maximum Marks</label>
                                    <input type="number"
                                        name="components[{{ $index }}][maximum_score]"
                                        value="{{ $component['maximum_score'] ?? '' }}"
                                        min="0.01" step="0.01" required
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
                            <span class="font-medium text-gray-700">Sum of component maximum marks</span>
                            <span class="font-bold text-gray-900" id="component-total">100.00</span>
                        </div>
                        <p id="total-message" class="mt-2 text-sm text-gray-500">
                            Component marks should equal the total maximum marks.
                        </p>
                    </div>
                </div>

                <button type="submit"
                    class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                    Save Assessment Scheme
                </button>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800">Existing Assessment Schemes</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Scheme</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Class</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Subject</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Session</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">Action</th>y-3 text-left text-xs font-semibold uppercase text-gray-500">Components</th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($schemes as $scheme)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $scheme->name }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">{{ $scheme->classLevel->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">{{ $scheme->subject->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">{{ $scheme->academicSession->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    @foreach ($scheme->components as $component)
                                        <div>
                                            {{ $component->name }}
                                            ({{ $component->maximum_score }})
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-gray-800">
                                    <td class="px-5 py-4 text-center">
    <a href="{{ route('assessment-schemes.edit', $scheme) }}"
       class="inline-block rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">
        Edit
    </a>
</td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                    No assessment schemes have been created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $schemes->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('components-container');
            const addButton = document.getElementById('add-ca');
            const totalInput = document.getElementById('total_marks');
            const sectionSelect = document.getElementById('school_section_id');
            const classSelect = document.getElementById('class_level_id');
            const subjectSelect = document.getElementById('subject_id');

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
                    <div class="md:col-span-5">
                        <label class="mb-1 block text-xs font-medium text-gray-600">Component Name</label>
                        <input type="text" name="components[${nextIndex}][name]"
                            placeholder="e.g. Test 2" required
                            class="w-full rounded-lg border-gray-300">
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
                            class="remove-component w-full rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
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

            function filterClasses() {
                const sectionId = sectionSelect.value;

                Array.from(classSelect.options).forEach(option => {
                    if (!option.value) return;
                    option.hidden = option.dataset.section !== sectionId;
                });

                if (classSelect.selectedOptions.length &&
                    classSelect.selectedOptions[0].hidden) {
                    classSelect.value = '';
                }

                filterSubjects();
            }

            async function filterSubjects() {
                const classId = classSelect.value;
                subjectSelect.innerHTML = '<option value="">Select subject</option>';

                if (!classId) return;

                const subjects = @json($subjects);

                const assigned = subjects.filter(subject =>
                    subject.class_levels.some(level => level.id == classId)
                );

                assigned.forEach(subject => {
                    if (subject.school_section_id == sectionSelect.value) {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    }
                });
            }

            sectionSelect.addEventListener('change', filterClasses);
            classSelect.addEventListener('change', filterSubjects);

            updateTotal();
            if (sectionSelect.value) filterClasses();
        });
    </script>
</x-admin-layout>
