<x-app-layout>

    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-2xl shadow-lg p-6 text-white">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <p class="text-blue-100 text-sm">
                        Welcome back
                    </p>

                    <h1 class="text-2xl md:text-3xl font-bold mt-1">
                        {{ Auth::user()->name }} 👋
                    </h1>

                    <p class="text-blue-100 mt-2">
                        Here's what's happening in your school today.
                    </p>
                </div>

                <div class="bg-white/10 rounded-xl px-5 py-4">

                    <p class="text-blue-100 text-xs uppercase tracking-wide">
                        Today's Date
                    </p>

                    <p class="font-semibold mt-1">
                        {{ date('l, d F Y') }}
                    </p>

                </div>

            </div>

        </div>


        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">


            <!-- Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Students
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            0
                        </h2>

                    </div>

                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                        👨‍🎓
                    </div>

                </div>

                <div class="mt-4 flex items-center text-sm">

                    <span class="text-blue-600 font-medium">
                        Registered
                    </span>

                    <span class="text-gray-400 ml-2">
                        students
                    </span>

                </div>

            </div>


            <!-- Teachers -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Teachers
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            0
                        </h2>

                    </div>

                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl">
                        👨‍🏫
                    </div>

                </div>

                <div class="mt-4 flex items-center text-sm">

                    <span class="text-green-600 font-medium">
                        Active
                    </span>

                    <span class="text-gray-400 ml-2">
                        teachers
                    </span>

                </div>

            </div>


            <!-- Classes -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Classes
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            0
                        </h2>

                    </div>

                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl">
                        🏫
                    </div>

                </div>

                <div class="mt-4 flex items-center text-sm">

                    <span class="text-purple-600 font-medium">
                        Active
                    </span>

                    <span class="text-gray-400 ml-2">
                        classes
                    </span>

                </div>

            </div>


            <!-- Subjects -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Subjects
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            0
                        </h2>

                    </div>

                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-2xl">
                        📚
                    </div>

                </div>

                <div class="mt-4 flex items-center text-sm">

                    <span class="text-orange-600 font-medium">
                        Available
                    </span>

                    <span class="text-gray-400 ml-2">
                        subjects
                    </span>

                </div>

            </div>

        </div>


        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


            <!-- Attendance Overview -->
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-center justify-between mb-6">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            Attendance Overview
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Today's student attendance
                        </p>

                    </div>

                    <span class="text-sm text-gray-400">
                        Today
                    </span>

                </div>


                <div class="grid grid-cols-3 gap-4">


                    <div class="bg-green-50 rounded-xl p-5 text-center">

                        <div class="text-2xl font-bold text-green-600">
                            0
                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            Present
                        </p>

                    </div>


                    <div class="bg-red-50 rounded-xl p-5 text-center">

                        <div class="text-2xl font-bold text-red-600">
                            0
                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            Absent
                        </p>

                    </div>


                    <div class="bg-yellow-50 rounded-xl p-5 text-center">

                        <div class="text-2xl font-bold text-yellow-600">
                            0
                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            Pending
                        </p>

                    </div>

                </div>


                <div class="mt-6">

                    <div class="flex justify-between text-sm mb-2">

                        <span class="text-gray-500">
                            Attendance rate
                        </span>

                        <span class="font-semibold text-gray-700">
                            0%
                        </span>

                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2">

                        <div
                            class="bg-green-500 h-2 rounded-full"
                            style="width: 0%"
                        ></div>

                    </div>

                </div>

            </div>


            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <h2 class="text-lg font-bold text-gray-800">
                    Quick Actions
                </h2>

                <p class="text-sm text-gray-500 mt-1 mb-5">
                    Common administrative tasks
                </p>


                <div class="space-y-3">


                    <a
                        href="#"
                        class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition"
                    >

                        <span class="w-9 h-9 bg-blue-600 text-white rounded-lg flex items-center justify-center">
                            +
                        </span>

                        <span class="font-medium">
                            Add Student
                        </span>

                    </a>


                    <a
                        href="#"
                        class="flex items-center gap-3 p-3 rounded-xl bg-green-50 text-green-700 hover:bg-green-100 transition"
                    >

                        <span class="w-9 h-9 bg-green-600 text-white rounded-lg flex items-center justify-center">
                            +
                        </span>

                        <span class="font-medium">
                            Add Teacher
                        </span>

                    </a>


                    <a
                        href="#"
                        class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 transition"
                    >

                        <span class="w-9 h-9 bg-purple-600 text-white rounded-lg flex items-center justify-center">
                            +
                        </span>

                        <span class="font-medium">
                            Create Class
                        </span>

                    </a>


                    <a
                        href="#"
                        class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 text-orange-700 hover:bg-orange-100 transition"
                    >

                        <span class="w-9 h-9 bg-orange-500 text-white rounded-lg flex items-center justify-center">
                            ✓
                        </span>

                        <span class="font-medium">
                            Enter Results
                        </span>

                    </a>

                </div>

            </div>

        </div>


        <!-- Bottom Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-center justify-between mb-5">

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            Recent Activity
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Latest activities in the system
                        </p>

                    </div>

                </div>


                <div class="flex flex-col items-center justify-center py-10 text-center">

                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center text-2xl">
                        📋
                    </div>

                    <p class="text-gray-600 font-medium mt-4">
                        No recent activity
                    </p>

                    <p class="text-gray-400 text-sm mt-1">
                        Activities will appear here as the system is used.
                    </p>

                </div>

            </div>


            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="mb-5">

                    <h2 class="text-lg font-bold text-gray-800">
                        Upcoming Events
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Important school activities
                    </p>

                </div>


                <div class="flex flex-col items-center justify-center py-10 text-center">

                    <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center text-2xl">
                        📅
                    </div>

                    <p class="text-gray-600 font-medium mt-4">
                        No upcoming events
                    </p>

                    <p class="text-gray-400 text-sm mt-1">
                        School events will appear here.
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>