
<x-admin-layout>
    <div class="mx-auto max-w-7xl space-y-6 p-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Student Promotion
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Promote students, record repeated classes, or transfer students between classes.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-100 p-4 text-red-800">
                <p class="font-semibold">Please correct the following errors:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STEP 1: SELECT SCHOOL SECTION AND CURRENT CLASS --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-800">
                1. Select the current class
            </h2>

            <form method="GET"
                  action="{{ route('promotions.index') }}"
                  class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>
                    <label for="school_section_id"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        School Section
                    </label>

                    <select id="school_section_id"
                            name="school_section_id"
                            required
                            class="w-full rounded-lg border-gray-300">
                        <option value="">Select section</option>

                        @foreach ($schoolSections as $section)
                            <option value="{{ $section->id }}"
                                @selected((string) request('school_section_id') === (string) $section->id)>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="class_arm_id"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Current Class
                    </label>

                    <select id="class_arm_id"
                            name="class_arm_id"
                            required
                            class="w-full rounded-lg border-gray-300">
                        <option value="">Select class</option>

                        @foreach ($classArms as $arm)
                            <option value="{{ $arm->id }}"
                                @selected((string) request('class_arm_id') === (string) $arm->id)>
                                {{ $arm->classLevel->name }} {{ $arm->name }}
                                ({{ $arm->classLevel->schoolSection->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700">
                        Load Students
                    </button>
                </div>
            </form>
        </div>

        {{-- STEP 2: SELECT STUDENTS AND RECORD DECISIONS --}}
        @if (request()->filled('class_arm_id') && request()->filled('school_section_id'))

            <form method="POST"
                  action="{{ route('promotions.store') }}"
                  class="space-y-6">

                @csrf

                <input type="hidden"
                       name="school_section_id"
                       value="{{ request('school_section_id') }}">

                <input type="hidden"
                       name="from_class_arm_id"
                       value="{{ request('class_arm_id') }}">

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold text-gray-800">
                            2. Select students
                        </h2>

                        @if ($students->isNotEmpty())
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox"
                                       id="select_all"
                                       class="rounded border-gray-300">
                                Select all students
                            </label>
                        @endif
                    </div>

                    @if ($students->isEmpty())

                        <p class="rounded-lg bg-yellow-50 p-4 text-yellow-800">
                            No active students were found in this class.
                        </p>

                    @else

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-sm">Select</th>
                                        <th class="px-3 py-3 text-left text-sm">Admission No.</th>
                                        <th class="px-3 py-3 text-left text-sm">Student Name</th>
                                        <th class="px-3 py-3 text-left text-sm">Current Class</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($students as $student)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <input type="checkbox"
                                                       name="student_ids[]"
                                                       value="{{ $student->id }}"
                                                       class="student-checkbox rounded border-gray-300">
                                            </td>

                                            <td class="px-3 py-3 text-sm">
                                                {{ $student->admission_number }}
                                            </td>

                                            <td class="px-3 py-3 text-sm">
                                                {{ $student->first_name }}
                                                {{ $student->middle_name }}
                                                {{ $student->last_name }}
                                            </td>

                                            <td class="px-3 py-3 text-sm">
                                                {{ $student->classArm?->classLevel?->name }}
                                                {{ $student->classArm?->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>

                @if ($students->isNotEmpty())

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                        <h2 class="mb-4 text-lg font-semibold text-gray-800">
                            3. Record promotion decision
                        </h2>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                            <div>
                                <label for="academic_session"
                                       class="mb-1 block text-sm font-medium text-gray-700">
                                    Academic Session
                                </label>

                                <input id="academic_session"
                                       name="academic_session"
                                       value="{{ old('academic_session') }}"
                                       placeholder="e.g. 2026-2027"
                                       pattern="[0-9]{4}-[0-9]{4}"
                                       required
                                       class="w-full rounded-lg border-gray-300">
                            </div>

                            <div>
                                <label for="term"
                                       class="mb-1 block text-sm font-medium text-gray-700">
                                    Term
                                </label>

                                <select id="term"
                                        name="term"
                                        class="w-full rounded-lg border-gray-300">
                                    <option value="">Select term (optional)</option>

                                    <option value="First Term"
                                        @selected(old('term') === 'First Term')>
                                        First Term
                                    </option>

                                    <option value="Second Term"
                                        @selected(old('term') === 'Second Term')>
                                        Second Term
                                    </option>

                                    <option value="Third Term"
                                        @selected(old('term') === 'Third Term')>
                                        Third Term
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="decision"
                                       class="mb-1 block text-sm font-medium text-gray-700">
                                    Decision
                                </label>

                                <select id="decision"
                                        name="decision"
                                        required
                                        class="w-full rounded-lg border-gray-300">

                                    <option value="promoted"
                                        @selected(old('decision', 'promoted') === 'promoted')>
                                        Promoted
                                    </option>

                                    <option value="repeated"
                                        @selected(old('decision') === 'repeated')>
                                        Repeated class
                                    </option>

                                    <option value="transferred"
                                        @selected(old('decision') === 'transferred')>
                                        Transfer to another class
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="to_class_arm_id"
                                       class="mb-1 block text-sm font-medium text-gray-700">
                                    Destination Class
                                </label>

                                <select id="to_class_arm_id"
                                        name="to_class_arm_id"
                                        required
                                        class="w-full rounded-lg border-gray-300">

                                    <option value="">Select destination class</option>

                                    @foreach ($classArms as $arm)
                                        <option value="{{ $arm->id }}"
                                            @selected((string) old('to_class_arm_id') === (string) $arm->id)>
                                            {{ $arm->classLevel->name }} {{ $arm->name }}
                                            ({{ $arm->classLevel->schoolSection->name }})
                                        </option>
                                    @endforeach
                                </select>

                                <p id="destination_help"
                                   class="mt-1 text-xs text-gray-500">
                                    Choose the class the students will move to.
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="remarks"
                                       class="mb-1 block text-sm font-medium text-gray-700">
                                    Remarks (optional)
                                </label>

                                <textarea id="remarks"
                                          name="remarks"
                                          rows="3"
                                          class="w-full rounded-lg border-gray-300"
                                          placeholder="Optional notes about this decision">{{ old('remarks') }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to save these promotion decisions?')"
                                    class="rounded-lg bg-green-600 px-5 py-3 font-semibold text-white hover:bg-green-700">
                                Save Promotion Decisions
                            </button>
                        </div>

                    </div>

                @endif

            </form>

        @endif

    </div>

    <script>
        const selectAll = document.getElementById('select_all');

        selectAll?.addEventListener('change', function () {
            document.querySelectorAll('.student-checkbox').forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });

        const decisionSelect = document.getElementById('decision');
        const destinationSelect = document.getElementById('to_class_arm_id');
        const destinationHelp = document.getElementById('destination_help');

        function updateDestinationField() {
            if (!decisionSelect || !destinationSelect || !destinationHelp) {
                return;
            }

            if (decisionSelect.value === 'repeated') {
                destinationSelect.required = false;
                destinationSelect.value = '';
                destinationSelect.disabled = true;

                destinationHelp.textContent =
                    'The students will remain in their current class automatically.';
            } else {
                destinationSelect.required = true;
                destinationSelect.disabled = false;

                destinationHelp.textContent =
                    'Choose a different destination class in the same school section.';
            }
        }

        decisionSelect?.addEventListener('change', updateDestinationField);
        updateDestinationField();
    </script>
</x-admin-layout>
