
<x-admin-layout>
    <div class="p-6">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Student Records</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Register, search, view, and manage student records.
                </p>
            </div>

            <a href="{{ route('students.create') }}"
               class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                + Register Student
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Search and Filters --}}
        <div class="mb-6 rounded-lg bg-white p-5 shadow-sm">
            <form action="{{ route('students.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

                    {{-- Search --}}
                    <div>
                        <label for="search" class="mb-1 block text-sm font-medium text-gray-700">
                            Search Students
                        </label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Name, admission number..."
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    {{-- School Section --}}
                    <div>
                        <label for="school_section_id" class="mb-1 block text-sm font-medium text-gray-700">
                            School Section
                        </label>
                        <select
                            id="school_section_id"
                            name="school_section_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All Sections</option>
                            @foreach ($schoolSections as $section)
                                <option
                                    value="{{ $section->id }}"
                                    @selected((string) request('school_section_id') === (string) $section->id)
                                >
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class and Arm --}}
                    <div>
                        <label for="class_arm_id" class="mb-1 block text-sm font-medium text-gray-700">
                            Class / Arm
                        </label>
                        <select
                            id="class_arm_id"
                            name="class_arm_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All Classes</option>
                            @foreach ($classArms as $arm)
                                <option
                                    value="{{ $arm->id }}"
                                    data-section="{{ $arm->classLevel->school_section_id }}"
                                    @selected((string) request('class_arm_id') === (string) $arm->id)
                                >
                                    {{ $arm->classLevel->name }} {{ $arm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-1 block text-sm font-medium text-gray-700">
                            Student Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All Statuses</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            <option value="graduated" @selected(request('status') === 'graduated')>Graduated</option>
                            <option value="transferred" @selected(request('status') === 'transferred')>Transferred</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        Search / Filter
                    </button>

                    <a
                        href="{{ route('students.index') }}"
                        class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200"
                    >
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        {{-- Student Table --}}
        <div class="overflow-hidden rounded-lg bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Admission No.
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Student Name
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Section
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Class / Arm
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Parent / Guardian
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-800">
                                    {{ $student->admission_number }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-800">
                                    {{ $student->first_name }}
                                    {{ $student->middle_name }}
                                    {{ $student->last_name }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $student->schoolSection->name ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                    @if ($student->classArm)
                                        {{ $student->classArm->classLevel->name ?? '' }}
                                        {{ $student->classArm->name }}
                                    @else
                                        <span class="text-gray-400">Not assigned</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    <div>{{ $student->parent_name ?: '—' }}</div>
                                    @if ($student->parent_phone)
                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $student->parent_phone }}
                                        </div>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-gray-100 text-gray-700',
                                            'graduated' => 'bg-blue-100 text-blue-800',
                                            'transferred' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$student->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        <a
                                            href="{{ route('students.show', $student) }}"
                                            class="font-medium text-blue-600 hover:text-blue-800"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('students.edit', $student) }}"
                                            class="font-medium text-indigo-600 hover:text-indigo-800"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('students.destroy', $student) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this student record?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="font-medium text-red-600 hover:text-red-800"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No student records found. Try changing your search or filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($students->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $students->links() }}
                </div>
            @endif

            <div class="border-t border-gray-100 px-5 py-3 text-sm text-gray-500">
                Showing {{ $students->firstItem() ?? 0 }}
                to {{ $students->lastItem() ?? 0 }}
                of {{ $students->total() }} student(s)
            </div>
        </div>
    </div>

    {{-- Keep class options consistent with the selected school section --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const section = document.getElementById('school_section_id');
            const classArm = document.getElementById('class_arm_id');

            function filterClassArms() {
                const sectionId = section.value;
                const selectedOption = classArm.options[classArm.selectedIndex];

                Array.from(classArm.options).forEach(function (option, index) {
                    if (index === 0) return;

                    option.hidden =
                        sectionId !== '' && option.dataset.section !== sectionId;
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
