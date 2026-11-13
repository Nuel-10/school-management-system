
<x-admin-layout title="Grading System">
    <div class="mx-auto max-w-7xl space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Grading System</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Configure grade boundaries and performance remarks for each school section.
                </p>
            </div>

            <a href="{{ route('grade-scales.create') }}"
               class="rounded-lg bg-blue-900 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                + Create Grading Scale
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-100 p-4 text-red-800">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800">
                    Existing Grading Scales
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Grading Scale
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                School Section
                            </th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                                Grades
                            </th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>
                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($gradeScales as $scale)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $scale->name }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $scale->schoolSection->name ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm text-gray-600">
                                    {{ $scale->boundaries->count() }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if ($scale->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('grade-scales.edit', $scale) }}"
                                           class="rounded-lg bg-blue-900 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-800">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('grade-scales.destroy', $scale) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this grading scale?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <tr class="bg-gray-50">
                                <td colspan="5" class="px-5 py-3">
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($scale->boundaries as $boundary)
                                            <div class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm">
                                                <span class="font-bold text-gray-800">
                                                    {{ $boundary->grade }}
                                                </span>

                                                <span class="ml-2 text-gray-600">
                                                    {{ number_format($boundary->minimum_percentage, 2) }}%
                                                    –
                                                    {{ number_format($boundary->maximum_percentage, 2) }}%
                                                </span>

                                                <span class="ml-2 text-gray-500">
                                                    {{ $boundary->remark }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                    No grading scales have been created yet.
                                    Create your first grading scale to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-admin-layout>
