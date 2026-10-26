<x-admin-layout title="Class Arms">

    <div class="p-6">

        <div class="mb-6 flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Class Arms
                </h2>

                <p class="mt-1 text-gray-600">
                    Manage class arms for each class level.
                </p>
            </div>

            <a href="{{ route('class-arms.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Add Class Arm
            </a>

        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">#</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">School Section</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Class Level</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Arm</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Code</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse ($classArms as $classArm)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $classArms->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $classArm->classLevel->schoolSection->name }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $classArm->classLevel->name }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $classArm->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $classArm->code ?? '—' }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($classArm->is_active)

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

                                    <a href="{{ route('class-arms.edit', $classArm) }}"
                                       class="mr-3 font-medium text-blue-600 hover:text-blue-800">
                                        Edit
                                    </a>

                                    <form action="{{ route('class-arms.destroy', $classArm) }}"
                                          method="POST"
                                          class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this class arm?')"
                                                class="font-medium text-red-600 hover:text-red-800">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    No class arms have been created yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($classArms->hasPages())

                <div class="border-t px-6 py-4">
                    {{ $classArms->links() }}
                </div>

            @endif

        </div>

    </div>

</x-admin-layout>