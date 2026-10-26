
<x-admin-layout>
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Student</h2>
            <p class="mt-1 text-gray-600">
                Update the student's personal, school, and parent/guardian details.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <p class="mb-2 font-semibold">Please correct the following errors:</p>
                <ul class="list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.update', $student) }}" method="POST"
              class="space-y-6 rounded-lg bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-gray-800">
                    Student Information
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="admission_number" class="mb-1 block text-sm font-medium text-gray-700">
                            Admission Number *
                        </label>
                        <input id="admission_number" name="admission_number" type="text" required
                               value="{{ old('admission_number', $student->admission_number) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="first_name" class="mb-1 block text-sm font-medium text-gray-700">
                            First Name *
                        </label>
                        <input id="first_name" name="first_name" type="text" required
                               value="{{ old('first_name', $student->first_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="middle_name" class="mb-1 block text-sm font-medium text-gray-700">
                            Middle Name
                        </label>
                        <input id="middle_name" name="middle_name" type="text"
                               value="{{ old('middle_name', $student->middle_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">
                            Last Name *
                        </label>
                        <input id="last_name" name="last_name" type="text" required
                               value="{{ old('last_name', $student->last_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="date_of_birth" class="mb-1 block text-sm font-medium text-gray-700">
                            Date of Birth
                        </label>
                        <input id="date_of_birth" name="date_of_birth" type="date"
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               max="{{ now()->toDateString() }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="gender" class="mb-1 block text-sm font-medium text-gray-700">
                            Gender
                        </label>
                        <select id="gender" name="gender"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select gender</option>
                            <option value="Male" @selected(old('gender', $student->gender) === 'Male')>Male</option>
                            <option value="Female" @selected(old('gender', $student->gender) === 'Female')>Female</option>
                        </select>
                    </div>

                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">
                            Student Phone
                        </label>
                        <input id="phone" name="phone" type="text"
                               value="{{ old('phone', $student->phone) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                            Student Email
                        </label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email', $student->email) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-gray-800">
                    School Placement
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="school_section_id" class="mb-1 block text-sm font-medium text-gray-700">
                            School Section *
                        </label>
                        <select id="school_section_id" name="school_section_id" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select section</option>
                            @foreach ($schoolSections as $section)
                                <option value="{{ $section->id }}"
                                    @selected((string) old('school_section_id', $student->school_section_id) === (string) $section->id)>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="class_arm_id" class="mb-1 block text-sm font-medium text-gray-700">
                            Class / Arm
                        </label>
                        <select id="class_arm_id" name="class_arm_id"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Not assigned</option>
                            @foreach ($classArms as $arm)
                                <option value="{{ $arm->id }}"
                                    data-section="{{ $arm->classLevel->school_section_id }}"
                                    @selected((string) old('class_arm_id', $student->class_arm_id) === (string) $arm->id)>
                                    {{ $arm->classLevel->name }} {{ $arm->name }}
                                    ({{ $arm->classLevel->schoolSection->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-1 block text-sm font-medium text-gray-700">
                            Student Status *
                        </label>
                        <select id="status" name="status" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'graduated' => 'Graduated', 'transferred' => 'Transferred'] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('status', $student->status) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-gray-800">
                    Parent / Guardian Information
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="parent_name" class="mb-1 block text-sm font-medium text-gray-700">
                            Parent / Guardian Name
                        </label>
                        <input id="parent_name" name="parent_name" type="text"
                               value="{{ old('parent_name', $student->parent_name) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="parent_phone" class="mb-1 block text-sm font-medium text-gray-700">
                            Parent / Guardian Phone
                        </label>
                        <input id="parent_phone" name="parent_phone" type="text"
                               value="{{ old('parent_phone', $student->parent_phone) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="parent_email" class="mb-1 block text-sm font-medium text-gray-700">
                            Parent / Guardian Email
                        </label>
                        <input id="parent_email" name="parent_email" type="email"
                               value="{{ old('parent_email', $student->parent_email) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 border-t pt-5">
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Changes
                </button>

                <a href="{{ route('students.show', $student) }}"
                   class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const section = document.getElementById('school_section_id');
            const classArm = document.getElementById('class_arm_id');

            function filterClassArms() {
                const sectionId = section.value;
                const selectedOption = classArm.options[classArm.selectedIndex];

                Array.from(classArm.options).forEach(function (option, index) {
                    if (index === 0) return;
                    option.hidden = sectionId !== '' &&
                        option.dataset.section !== sectionId;
                });

                if (selectedOption && selectedOption.hidden) {
                    classArm.value = '';
                }
            }

            section.addEventListener('change', filterClassArms);
            filterClassArms();
        });
    </script>
</x-admin-layout>
