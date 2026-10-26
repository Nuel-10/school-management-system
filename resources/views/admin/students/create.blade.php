
<x-admin-layout>
    <div class="p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Register Student</h2>
            <p class="mt-1 text-gray-600">
                Enter the student's personal, school and parent/guardian details.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST"
              class="max-w-5xl rounded-lg bg-white p-6 shadow-sm">
            @csrf

            <h3 class="mb-4 border-b pb-2 text-lg font-semibold text-gray-800">
                Student Information
            </h3>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="admission_number" class="mb-2 block text-sm font-medium text-gray-700">
                        Admission Number *
                    </label>

    <input id="admission_number" name="admission_number"
           value="Generated automatically"
           readonly
           class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600 shadow-sm">

    <p class="mt-1 text-sm text-gray-500">
        The system will generate the admission number automatically when you register this student.
    </p>
</div>

<div>
    <label for="first_name" class="mb-2 block text-sm font-medium text-gray-700">
                    </label>
                    <input id="first_name" name="first_name" value="{{ old('first_name') }}"
                           required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="middle_name" class="mb-2 block text-sm font-medium text-gray-700">
                        Middle Name
                    </label>
                    <input id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">
                        Last Name *
                    </label>
                    <input id="last_name" name="last_name" value="{{ old('last_name') }}"
                           required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="date_of_birth" class="mb-2 block text-sm font-medium text-gray-700">
                        Date of Birth
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="gender" class="mb-2 block text-sm font-medium text-gray-700">
                        Gender
                    </label>
                    <select id="gender" name="gender"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select gender</option>
                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                    </select>
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
                        Student Phone
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                        Student Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <h3 class="mb-4 mt-8 border-b pb-2 text-lg font-semibold text-gray-800">
                School Placement
            </h3>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="school_section_id" class="mb-2 block text-sm font-medium text-gray-700">
                        School Section *
                    </label>
                    <select id="school_section_id" name="school_section_id" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    <label for="class_arm_id" class="mb-2 block text-sm font-medium text-gray-700">
                        Class / Arm
                    </label>
                    <select id="class_arm_id" name="class_arm_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Assign class later</option>
                        @foreach ($classArms as $arm)
                            <option value="{{ $arm->id }}"
                                data-section="{{ $arm->classLevel->school_section_id }}"
                                @selected(old('class_arm_id') == $arm->id)>
                                {{ $arm->classLevel->schoolSection->name }} —
                                {{ $arm->classLevel->name }} {{ $arm->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Choose a class arm from the selected school section.
                    </p>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                        Student Status *
                    </label>
                    <select id="status" name="status" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'graduated' => 'Graduated', 'transferred' => 'Transferred'] as $value => $label)
                            <option value="{{ $value }}"
                                @selected(old('status', 'active') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h3 class="mb-4 mt-8 border-b pb-2 text-lg font-semibold text-gray-800">
                Parent / Guardian Information
            </h3>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label for="parent_name" class="mb-2 block text-sm font-medium text-gray-700">
                        Parent / Guardian Name
                    </label>
                    <input id="parent_name" name="parent_name" value="{{ old('parent_name') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="parent_phone" class="mb-2 block text-sm font-medium text-gray-700">
                        Parent / Guardian Phone
                    </label>
                    <input type="tel" id="parent_phone" name="parent_phone"
                           value="{{ old('parent_phone') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="parent_email" class="mb-2 block text-sm font-medium text-gray-700">
                        Parent / Guardian Email
                    </label>
                    <input type="email" id="parent_email" name="parent_email"
                           value="{{ old('parent_email') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Register Student
                </button>

                <a href="{{ route('students.index') }}"
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
