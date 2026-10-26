<x-admin-layout title="Edit Class Level">

    <div class="p-6">

        {{-- Page Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Edit Class Level
            </h2>

            <p class="mt-1 text-gray-600">
                Update the details of this class level.
            </p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div class="max-w-3xl rounded-lg bg-white p-6 shadow-sm">

            <form action="{{ route('class-levels.update', $classLevel) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- School Section --}}
                <div class="mb-5">
                    <label for="school_section_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        School Section
                    </label>

                    <select name="school_section_id"
                            id="school_section_id"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        @foreach ($schoolSections as $section)
                            <option value="{{ $section->id }}"
                                {{ old('school_section_id', $classLevel->school_section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Class Level Name --}}
                <div class="mb-5">
                    <label for="name"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Class Level Name
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $classLevel->name) }}"
                           required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Code --}}
                <div class="mb-5">
                    <label for="code"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Code
                    </label>

                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code', $classLevel->code) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Display Order --}}
                <div class="mb-5">
                    <label for="display_order"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Display Order
                    </label>

                    <input type="number"
                           name="display_order"
                           id="display_order"
                           value="{{ old('display_order', $classLevel->display_order) }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Active Status --}}
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $classLevel->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">

                        <span class="ml-2 text-sm text-gray-700">
                            Active class level
                        </span>
                    </label>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Update Class Level
                    </button>

                    <a href="{{ route('class-levels.index') }}"
                       class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</x-admin-layout>