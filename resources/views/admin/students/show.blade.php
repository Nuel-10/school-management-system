
<x-admin-layout>
    <div class="p-6">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Student Profile</h2>
                <p class="mt-1 text-gray-600">
                    View the student's registration information.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('students.edit', $student) }}"
                   class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Edit Student
                </a>

                <a href="{{ route('students.index') }}"
                   class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                    Back to Records
                </a>
            </div>
        </div>

        <div class="mb-6 rounded-lg bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-700">
                    {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800">
                        {{ $student->first_name }}
                        {{ $student->middle_name }}
                        {{ $student->last_name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        Admission No: {{ $student->admission_number }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ ucfirst($student->status) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="mb-5 border-b pb-3 text-lg font-semibold text-gray-800">
                    Personal Information
                </h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">First Name</dt>
                        <dd class="font-medium text-gray-800">{{ $student->first_name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Middle Name</dt>
                        <dd class="font-medium text-gray-800">{{ $student->middle_name ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Last Name</dt>
                        <dd class="font-medium text-gray-800">{{ $student->last_name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Date of Birth</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $student->date_of_birth?->format('d M Y') ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Gender</dt>
                        <dd class="font-medium text-gray-800">{{ $student->gender ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Phone</dt>
                        <dd class="font-medium text-gray-800">{{ $student->phone ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="font-medium text-gray-800">{{ $student->email ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="mb-5 border-b pb-3 text-lg font-semibold text-gray-800">
                    School Information
                </h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">School Section</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $student->schoolSection->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Class Level</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $student->classArm?->classLevel?->name ?? 'Not assigned' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Class Arm</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $student->classArm?->name ?? 'Not assigned' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Student Status</dt>
                        <dd class="font-medium text-gray-800">{{ ucfirst($student->status) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Date Registered</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $student->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="mb-5 border-b pb-3 text-lg font-semibold text-gray-800">
                    Parent / Guardian Information
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <dt class="text-sm text-gray-500">Parent / Guardian Name</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $student->parent_name ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Parent / Guardian Phone</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $student->parent_phone ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Parent / Guardian Email</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $student->parent_email ?: '—' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
