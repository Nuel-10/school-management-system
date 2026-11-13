<x-admin-layout title="Report Card Settings">
    <div class="mx-auto max-w-7xl space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Report Card Settings</h1>
            <p class="mt-1 text-sm text-gray-600">
                Configure report card templates and control which sections appear.
            </p>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 font-medium text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-100 p-4 text-red-800">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('report-cards.settings.update') }}"
                  class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            School Section
                        </label>
                        <select name="school_section_id"
                                class="w-full rounded-lg border-gray-300">
                            <option value="">General / All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Template Name
                        </label>
                        <input type="text" name="template_name"
                               value="{{ old('template_name', 'Modern Professional') }}"
                               required
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Report Card Design
                        </label>
                        <select name="template_key"
                                class="w-full rounded-lg border-gray-300">
                            <option value="modern">Modern Professional</option>
                            <option value="classic">Classic Academic</option>
                            <option value="compact">Compact Layout</option>
                        </select>
                    </div>
                </div>

                <div>
                    <h2 class="mb-3 text-lg font-bold text-gray-900">
                        Display Sections
                    </h2>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @php
                            $toggles = [
                                'show_teacher_comment' => 'Class Teacher Comment',
                                'show_principal_comment' => 'Principal Comment',
                                'show_behaviour' => 'Behaviour Assessment',
                                'show_psychomotor' => 'Psychomotor Assessment',
                                'show_affective' => 'Affective Domain',
                                'show_attendance' => 'Attendance Record',
                                'show_class_position' => 'Class Position',
                                'show_next_term_date' => 'Next Term Resumption Date',
                                'is_active' => 'Set as Active Template',
                            ];
                        @endphp

                        @foreach($toggles as $field => $label)
                            <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                       checked
                                       class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="rounded-lg px-6 py-3 font-bold text-white shadow"
                            style="background-color:#4f46e5;">
                        Save Report Card Settings
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-gray-900">
                Saved Report Card Settings
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Section</th>
                            <th class="p-3">Template</th>
                            <th class="p-3">Design</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting)
                            <tr class="border-t">
                                <td class="p-3">
                                    {{ $setting->schoolSection->name ?? 'General' }}
                                </td>
                                <td class="p-3">{{ $setting->template_name }}</td>
                                <td class="p-3">{{ ucfirst($setting->template_key) }}</td>
                                <td class="p-3">
                                    @if($setting->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No report card settings saved yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>