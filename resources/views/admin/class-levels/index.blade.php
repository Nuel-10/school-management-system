<x-admin-layout title="Class Levels">

    <div class="p-6">

        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Class Levels
                </h2>

                <p class="mt-1 text-gray-600">
                    Manage class levels for each school section.
                </p>
            </div>

            <a href="{{ route('class-levels.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Add Class Level
            </a>

        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Class Levels Table --}}
        <div class="overflow-hidden rounded-lg bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">
                                #
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                School Section
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Class Level
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Code
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Class Arms
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-gray-700">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse ($classLevels as $classLevel)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $classLevels->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $classLevel->schoolSection->name }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $classLevel->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $classLevel->code ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $classLevel->classArms->count() }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($classLevel->is_active)

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Active
                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4 text-right">

                                    <a href="{{ route('class-levels.edit', $classLevel) }}"
                                       class="mr-3 font-medium text-blue-600 hover:text-blue-800">
                                        Edit
                                    </a>

                                    <form action="{{ route('class-levels.destroy', $classLevel) }}"
                                          method="POST"
                                          class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this class level?')"
                                                class="font-medium text-red-600 hover:text-red-800">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    No class levels have been created yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($classLevels->hasPages())

                <div class="border-t px-6 py-4">
                    {{ $classLevels->links() }}
                </div>

            @endif

        </div>

    </div>

</x-admin-layout>