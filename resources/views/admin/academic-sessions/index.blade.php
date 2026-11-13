
<x-admin-layout title="Academic Session Management">
    <div class="mx-auto max-w-7xl space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Academic Session Management</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage current, previous and historical academic sessions.
            </p>
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

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-800">Create Academic Session</h2>

            <form method="POST" action="{{ route('academic-sessions.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Session Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. 2026/2027"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Start Date (Optional)
                        </label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            End Date (Optional)
                        </label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="hidden" name="is_current" value="0">
<input type="checkbox" name="is_current" value="1"
    @checked(old('is_current'))
    class="rounded border-gray-300 text-blue-600">
                        Set as current session
                    </label>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="hidden" name="is_active" value="0">
<input type="checkbox" name="is_active" value="1"
    @checked(old('is_active', '1'))
    class="rounded border-gray-300 text-blue-600">
                        Active session
                    </label>
                </div>

                <button type="submit"
                    class="rounded-lg bg-blue-900 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                    Save Academic Session
                </button>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800">Academic Sessions</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Session</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Current</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Update</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($sessions as $session)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $session->name }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $session->start_date?->format('d M Y') ?? 'Not specified' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $session->end_date?->format('d M Y') ?? 'Not specified' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($session->is_current)
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">Current</span>
                                    @else
                                        <span class="text-sm text-gray-500">No</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{ $session->is_active ? 'Active' : 'Inactive' }}
                                </td>

                                <td class="px-6 py-4">
                                    <form method="POST"
                                        action="{{ route('academic-sessions.update', $session) }}"
                                        class="min-w-64 space-y-2">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="name" value="{{ $session->name }}"
                                            required
                                            class="w-full rounded border-gray-300 text-sm">

                                        <input type="date" name="start_date"
                                            value="{{ $session->start_date?->format('Y-m-d') }}"
                                            class="w-full rounded border-gray-300 text-sm">

                                        <input type="date" name="end_date"
                                            value="{{ $session->end_date?->format('Y-m-d') }}"
                                            class="w-full rounded border-gray-300 text-sm">

                                        <label class="flex items-center gap-2 text-xs">
                                            <input type="checkbox" name="is_current" value="1"
                                                @checked($session->is_current)>
                                            Current session
                                        </label>

                                        <label class="flex items-center gap-2 text-xs">
                                            <input type="checkbox" name="is_active" value="1"
                                                @checked($session->is_active)>
                                            Active
                                        </label>

                                        <button type="submit"
                                            class="rounded bg-gray-800 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-700">
                                            Update Session
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No academic sessions have been created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
