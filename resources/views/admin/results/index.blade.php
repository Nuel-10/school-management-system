
<x-admin-layout title="Student Results">
    <div class="mx-auto max-w-6xl space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Student Results</h1>
            <p class="mt-1 text-sm text-gray-500">
                Select a class, subject, academic session and term to enter student results.
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
            <h2 class="mb-5 text-lg font-semibold text-gray-800">
                Select Result Details
            </h2>

            <form method="POST"
                  action="{{ route('results.students') }}"
                  class="space-y-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Class
                        </label>

                        <select name="class_arm_id"
                                id="class_arm_id"
                                required
                                class="w-full rounded-lg border-gray-300">
                            <option value="">Select class</option>

                            @foreach ($classArms as $arm)
                                <option value="{{ $arm->id }}"
                                    data-class-level="{{ $arm->class_level_id }}"
                                    @selected(old('class_arm_id') == $arm->id)>
                                    {{ $arm->classLevel->name ?? 'Unknown Class' }}
                                    - {{ $arm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Subject
                        </label>

                        <select name="subject_id"
                                id="subject_id"
                                required
                                disabled
                                class="w-full rounded-lg border-gray-300">
                            <option value="">Select class first</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Academic Session
                        </label>

                        <select name="academic_session_id"
                                required
                                class="w-full rounded-lg border-gray-300">
                            <option value="">Select session</option>

                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}"
                                    @selected(old('academic_session_id') == $session->id || (!old('academic_session_id') && $session->is_current))>
                                    {{ $session->name }}
                                    {{ $session->is_current ? '(Current)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Term
                        </label>

                        <select name="term"
                                required
                                class="w-full rounded-lg border-gray-300">
                            <option value="">Select term</option>
                            @foreach (['First Term', 'Second Term', 'Third Term'] as $term)
                                <option value="{{ $term }}"
                                    @selected(old('term') == $term)>
                                    {{ $term }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="border-t pt-5">
                    <button type="submit"
                            class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                        Load Students for Result Entry
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classSelect = document.getElementById('class_arm_id');
            const subjectSelect = document.getElementById('subject_id');
            const oldSubjectId = @json(old('subject_id'));

            const subjects = @json($subjects);

            function loadSubjects() {
                const selectedOption = classSelect.selectedOptions[0];
                const classLevelId = selectedOption
                    ? selectedOption.dataset.classLevel
                    : null;

                subjectSelect.innerHTML = '<option value="">Select subject</option>';
                subjectSelect.disabled = true;

                if (!classLevelId) {
                    return;
                }

                const assignedSubjects = subjects.filter(subject =>
                    subject.class_levels.some(level =>
                        String(level.id) === String(classLevelId)
                    )
                );

                assignedSubjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;

                    if (String(subject.id) === String(oldSubjectId)) {
                        option.selected = true;
                    }

                    subjectSelect.appendChild(option);
                });

                subjectSelect.disabled = false;

                if (assignedSubjects.length === 0) {
                    subjectSelect.innerHTML =
                        '<option value="">No subjects assigned to this class</option>';
                    subjectSelect.disabled = true;
                }
            }

            classSelect.addEventListener('change', loadSubjects);

            if (classSelect.value) {
                loadSubjects();
            }
        });
    </script>
</x-admin-layout>
