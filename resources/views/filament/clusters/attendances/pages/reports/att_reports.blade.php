<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('Attendance.reports') }}
        </x-slot>

        <div class="fi-page-content-wrapper">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- Link Card 1 --}}
                <a href="https://www.example.com/report1" target="_blank" rel="noopener noreferrer" class="
                    block p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 transition duration-200
                    hover:shadow-md hover:ring-gray-950/10 dark:bg-gray-800 dark:ring-white/10 dark:hover:ring-white/20
                ">
                    <div class="flex items-center space-x-4">
                        <x-filament::icon
                            icon="heroicon-o-chart-bar"
                            class="h-8 w-8 text-primary-500 dark:text-primary-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                                {{ __('Attendance.report_room') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Attendance.report_room') }}
                            </p>
                        </div>
                    </div>
                </a>

                {{-- Link Card 2 --}}
                <a href="https://www.another-example.com/timesheet-report" target="_blank" rel="noopener noreferrer" class="
                    block p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 transition duration-200
                    hover:shadow-md hover:ring-gray-950/10 dark:bg-gray-800 dark:ring-white/10 dark:hover:ring-white/20
                ">
                    <div class="flex items-center space-x-4">
                        <x-filament::icon
                            icon="heroicon-o-clock"
                            class="h-8 w-8 text-success-500 dark:text-success-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                                {{ __('Attendance.report_staff') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Attendance.report_staff') }}
                            </p>
                        </div>
                    </div>
                </a>

                {{-- Link Card 3 --}}
                <a href="https://www.your-app.com/employee-portal" target="_blank" rel="noopener noreferrer" class="
                    block p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 transition duration-200
                    hover:shadow-md hover:ring-gray-950/10 dark:bg-gray-800 dark:ring-white/10 dark:hover:ring-white/20
                ">
                    <div class="flex items-center space-x-4">
                        <x-filament::icon
                            icon="heroicon-o-users"
                            class="h-8 w-8 text-info-500 dark:text-info-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                                {{ __('Attendance.report_groping') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Attendance.report_groping') }}
                            </p>
                        </div>
                    </div>

                </a>
                <a href="https://www.your-app.com/employee-portal" target="_blank" rel="noopener noreferrer" class="
                    block p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 transition duration-200
                    hover:shadow-md hover:ring-gray-950/10 dark:bg-gray-800 dark:ring-white/10 dark:hover:ring-white/20
                ">
                    <div class="flex items-center space-x-4">
                        <x-filament::icon
                            icon="heroicon-o-users"
                            class="h-8 w-8 text-info-500 dark:text-info-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                                {{ __('Attendance.report_attendance') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Attendance.report_attendance') }}
                            </p>
                        </div>
                    </div>

                </a>
                <a href="https://www.your-app.com/employee-portal" target="_blank" rel="noopener noreferrer" class="
                    block p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 transition duration-200
                    hover:shadow-md hover:ring-gray-950/10 dark:bg-gray-800 dark:ring-white/10 dark:hover:ring-white/20
                ">
                    <div class="flex items-center space-x-4">
                        <x-filament::icon
                            icon="heroicon-o-academic-cap"
                            class="h-8 w-8 text-info-500 dark:text-info-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                                {{ __('Attendance.report_exam') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Attendance.report_exam') }}
                            </p>
                        </div>
                    </div>

                </a>

                {{-- Add more link cards as needed --}}
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
