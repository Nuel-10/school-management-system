
<x-admin-layout title="Subject Management">
    <div class="mx-auto max-w-7xl space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Subject Management</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Create subjects and assign them to class levels.
                </p>
            </div>
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
            <h2 class="mb-5 text-lg font-semibold text-gray-800">Add New Subject</h2>

            <form method="POST" action="{{ route('subjects.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="school_section_id" class="mb-2 block text-sm font-medium text-gray-700">
                            School Section
                        </label>
                        <select
                            id="school_section_id"
                            name="school_section_id"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
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
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Subject Name
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Mathematics"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="code" class="mb-2 block text-sm font-medium text-gray-700">
                            Subject Code (Optional)
                        </label>
                        <input
                            id="code"
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            placeholder="e.g. MAT101"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="is_compulsory" class="mb-2 block text-sm font-medium text-gray-700">
                            Subject Type
                        </label>
                        <select
                            id="is_compulsory"
                            name="is_compulsory"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="1" @selected(old('is_compulsory', '1') == '1')>Compulsory</option>
                            <option value="0" @selected(old('is_compulsory') == '0')>Elective</option>
                        </select>
                    </div>
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-semibold text-gray-700">Assign to Class Levels</h3>

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($schoolSections as $section)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <h4 class="mb-3 font-semibold text-blue-900">
                                    {{ $section->name }}
                                </h4>

                                @forelse ($section->classLevels as $level)
                                    <label class="mb-2 flex items-center gap-2 text-sm text-gray-700">
                                        <input
                                            type="checkbox"
                                            name="class_level_ids[]"
                                            value="{{ $level->id }}"
                                            @checked(in_array($level->id, old('class_level_ids', [])))
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        >
                                        {{ $level->name }}
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No active class levels.</p>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800"
                >
                    Save Subject
                </button>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800">Existing Subjects</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Section</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Assigned Classes</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($subjects as $subject)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $subject->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $subject->code ?: '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $subject->schoolSection->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @forelse ($subject->classLevels as $level)
                                        <div>
                                            {{ $level->name }}
                                            <span class="text-xs text-gray-500">
                                                ({{ $level->pivot->is_compulsory ? 'Compulsory' : 'Elective' }})
                                            </span>
                                        </div>
                                    @empty
                                        —
                                    @endforelse
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form
                                        method="POST"
                                        action="{{ route('subjects.destroy', $subject) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this subject?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No subjects have been added yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $subjects->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>
