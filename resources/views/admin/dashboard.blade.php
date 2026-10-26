<x-admin-layout title="Admin Dashboard">

    <div class="p-6">

        {{-- Page Heading --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Admin Dashboard
            </h2>

            <p class="mt-1 text-gray-600">
                Welcome to the School Management System.
            </p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Students --}}
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">
                    Students
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800">
                    0
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Total students
                </p>
            </div>

            {{-- Teachers --}}
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">
                    Teachers
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800">
                    0
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Total teachers
                </p>
            </div>

            {{-- Classes --}}
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">
                    Classes
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800">
                    0
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Active classes
                </p>
            </div>

            {{-- Sections --}}
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-500">
                    School Sections
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $schoolSections->count() }}
                </p>

                <div class="mt-2 space-y-1">
    @foreach ($schoolSections as $section)
        <p class="text-sm text-gray-500">
            {{ $section->name }}
        </p>
    @endforeach
</div>
            </div>

        </div>

        {{-- Recent Activities --}}
        <div class="mt-8 rounded-lg bg-white p-6 shadow-sm">

            <h3 class="text-lg font-semibold text-gray-800">
                Recent Activities
            </h3>

            <div class="mt-4 border-t border-gray-200 pt-4">

                <p class="text-sm text-gray-500">
                    No recent activities yet.
                </p>

            </div>

        </div>

    </div>

</x-admin-layout>