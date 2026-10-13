<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف درجات الطلاب</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Arabic font support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">كشف درجات الطلاب</h3>
                </div>
                <h5 class="mt-2 text-blue-100">الدورة: {{ $batch->name }} - الدورة التدريبية: {{ $batch->course->name }}</h5>
            </div>

            <!-- Card Body -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider align-middle">
                                الرقم الجامعي
                            </th>
                            <th rowspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider align-middle">
                                اسم الطالب
                            </th>
                            <th colspan="{{ $subjects->count() }}" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المقررات الدراسية
                            </th>
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]" title="{{ $subject->subject_name_en }}">
                                    {{ $subject->name_ar }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">
                                {{ $student->university_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                {{ $student->name_ar }}
                            </td>

                            @foreach($subjects as $subject)
                                @php
                                    $mark = $student->marks->firstWhere('subject_code', $subject->code);
                                    $grade = $mark ? $mark->grade : '-';

                                    $gradeClasses = '';
                                    if ($grade == 'F' || $grade == 'D') {
                                        $gradeClasses = 'text-red-600 font-bold';
                                    } elseif ($grade == 'A' || $grade == 'B') {
                                        $gradeClasses = 'text-green-600 font-bold';
                                    }
                                @endphp
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-center {{ $gradeClasses }}" title="{{ $subject->subject_name_en }}">
                                    {{ $grade }}
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-2 md:mb-0">
                        <span class="font-medium">إجمالي الطلاب:</span>
                        <span class="text-gray-700">{{ $students->count() }}</span>
                    </div>
                    <div>
                        <a href="{{ route('fialment') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-arrow-left ml-2"></i>
                            رجوع
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional: Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
