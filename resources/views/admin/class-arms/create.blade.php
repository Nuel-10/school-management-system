<x-admin-layout title="Add Class Arm">

    <div class="p-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Add Class Arm
            </h2>

            <p class="mt-1 text-gray-600">
                Create a class arm under an existing class level.
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

        <div class="max-w-3xl rounded-lg bg-white p-6 shadow-sm">

            <form action="{{ route('class-arms.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="class_level_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Class Level
                    </label>

                    <select name="class_level_id"
                            id="class_level_id"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        <option value="">Select class level</option>

                        @foreach ($classLevels as $classLevel)
                            <option value="{{ $classLevel->id }}"
                                {{ old('class_level_id') == $classLevel->id ? 'selected' : '' }}>
                                {{ $classLevel->schoolSection->name }} —
                                {{ $classLevel->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-5">
                    <label for="name"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Class Arm Name
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Primary 1A"
                           required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    <p class="mt-1 text-xs text-gray-500">
                        Example: Primary 1A, Primary 1B, JSS 1A.
                    </p>
                </div>

                <div class="mb-5">
                    <label for="code"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Code
                    </label>

                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code') }}"
                           placeholder="e.g. P1A"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    <p class="mt-1 text-xs text-gray-500">
                        Optional short code for this class arm.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">

                        <span class="ml-2 text-sm text-gray-700">
                            Active class arm
                        </span>
                    </label>
                </div>

                <div class="flex items-center gap-3">

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Save Class Arm
                    </button>

                    <a href="{{ route('class-arms.index') }}"
                       class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</x-admin-layout>