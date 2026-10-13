<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف الدرجات - {{ $course->name_ar }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 10mm;

            @top-center {
                content: element(header);
            }

            @bottom-center {
                content: element(footer);
            }
        }

        #header {
            position: running(header);
        }

        #footer {
            position: running(footer);
        }

        .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding-top: 50px;
                padding-bottom: 80px;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
<div class="container mx-auto w-[95%] py-8">
    <!-- Header -->
    @include('grades.headerPrint')


        <!-- Student Grades Table -->
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full border border-gray-100">
                <thead>
                    <tr>
                        <th rowspan="2" class="border border-gray-300 bg-gray-100 p-2">#</th>
                        <th rowspan="2" class="border border-gray-300 bg-gray-100 p-2">الرقم الجامعي</th>
                        <th rowspan="2" class="border border-gray-300 bg-gray-100 p-2">اسم الطالب</th>
                        @foreach ($subjects as $subject)
                        <th class="border border-gray-300 bg-gray-100 p-1 vertical-text h-32">{{ $subject->name_ar }}</th>
                        @endforeach
                        <th rowspan="2" class="border border-gray-300 bg-gray-100 p-2">المعدل</th>
                    </tr>
                    <tr>
                        @foreach ($subjects as $subject)
                        <th class="border border-gray-300 bg-gray-100 p-1 vertical-text h-32">({{ $subject->code }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="border border-gray-300 p-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 p-2 text-center">{{ $student->university_no }}</td>
                        <td class="border border-gray-300 p-2">{{ $student->name_ar }}</td>

                        @php
                        $totalMarks = 0;
                        $totalSubjects = 0;
                        @endphp

                        @foreach ($subjects as $subject)
                        @php
                        $mark = $student->marks->firstWhere('subject_code', $subject->code);
                        $markValue = $mark ? $mark->mark : '-';
                        $grade = $mark && is_numeric($markValue) ? $mark->grade : '-';

                        if (is_numeric($markValue)) {
                        $totalMarks += $markValue;
                        $totalSubjects++;
                        }
                        @endphp
                        <td class="border border-gray-300 p-2 text-center {{ ($mark && $mark->mark < 50) ? 'bg-gray-200' : '' }}">
                            {{ $markValue }}
                            @if ($grade != '-')
                            <br><span class="text-xs">({{ $grade }})</span>
                            @endif
                        </td>
                        @endforeach

                        <td class="border border-gray-300 p-2 text-center font-semibold">
                            @php
                            $mark = $student->marks->firstWhere('subject_code', $subject->code);
                            $markValue = $mark ? $mark->mark : '-';
                            $grade = $mark && is_numeric($markValue) ? App\Models\Result\Grade::getGradeTitle($markValue) : null;
                            @endphp

                            {{ $markValue }}
                            @if ($grade)
                            <br><span class="text-xs">({{ $grade->titlear }})</span>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Subject Analysis Section -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4 text-center">تحليل نتائج المواد</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($subjects as $subject)
                @php
                $marks = [];
                $passed = 0;
                $failed = 0;

                foreach ($students as $student) {
                $mark = $student->marks->firstWhere('subject_code', $subject->code);
                if ($mark) {
                $marks[] = $mark->mark;
                if ($mark->mark >= 50) {
                $passed++;
                } else {
                $failed++;
                }
                }
                }

                $total = count($marks);
                $passPercentage = $total > 0 ? round(($passed / $total) * 100, 2) : 0;
                $avgMark = $total > 0 ? round(array_sum($marks) / $total, 2) : 0;
                $maxMark = $total > 0 ? max($marks) : 0;
                $minMark = $total > 0 ? min($marks) : 0;
                @endphp

                <div class="border border-gray-300 rounded-lg p-4 bg-white">
                    <h4 class="font-bold text-lg mb-2">{{ $subject->name_ar }} ({{ $subject->code }})</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>عدد الطلاب:</div>
                        <div class="font-semibold">{{ $total }}</div>

                        <div>الناجحون:</div>
                        <div class="font-semibold text-green-600">{{ $passed }}</div>

                        <div>الراسبون:</div>
                        <div class="font-semibold text-red-600">{{ $failed }}</div>

                        <div>نسبة النجاح:</div>
                        <div class="font-semibold">{{ $passPercentage }}%</div>

                        <div>متوسط الدرجات:</div>
                        <div class="font-semibold">{{ $avgMark }}</div>

                        <div>أعلى درجة:</div>
                        <div class="font-semibold">{{ $maxMark }}</div>

                        <div>أقل درجة:</div>
                        <div class="font-semibold">{{ $minMark }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Overall Summary -->
        <div class="border-t-2 border-gray-300 pt-6">
            <h3 class="text-xl font-bold mb-4 text-center">ملخص عام للنتائج</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="border border-gray-300 rounded-lg p-4 bg-white">
                    <h4 class="font-bold text-lg mb-2 text-center">إحصائيات الطلاب</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>عدد الطلاب:</div>
                        <div class="font-semibold">{{ count($students) }}</div>

                        <div>الناجحون في جميع المواد:</div>
                        <div class="font-semibold">
                            @php
                            $allPassed = 0;
                            foreach ($students as $student) {
                            $passedAll = true;
                            foreach ($subjects as $subject) {
                            $mark = $student->marks->firstWhere('subject_code', $subject->code);
                            if (!$mark || $mark->mark < 50) { $passedAll=false; break; } } if ($passedAll) $allPassed++; } @endphp {{ $allPassed }} </div>

                                <div>الراسبون في مادة واحدة على الأقل:</div>
                                <div class="font-semibold">{{ count($students) - $allPassed }}</div>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-lg p-4 bg-white">
                        <h4 class="font-bold text-lg mb-2 text-center">أفضل الطلاب</h4>
                        @php
                        $averages = [];
                        foreach ($students as $student) {
                        $total = 0;
                        $count = 0;
                        foreach ($subjects as $subject) {
                        $mark = $student->marks->firstWhere('subject_code', $subject->code);
                        if ($mark && is_numeric($mark->mark)) {
                        $total += $mark->mark;
                        $count++;
                        }
                        }
                        if ($count > 0) {
                        $averages[$student->university_no] = [
                        'name' => $student->name_ar,
                        'avg' => $total / $count
                        ];
                        }
                        }
                        arsort($averages);
                        $topStudents = array_slice($averages, 0, 3, true);
                        @endphp

                        @foreach ($topStudents as $id => $student)
                        <div class="mb-1">
                            <span class="font-semibold">{{ $loop->iteration }}. {{ $student['name'] }}</span>
                            <span class="text-blue-600">({{ round($student['avg'], 2) }})</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border border-gray-300 rounded-lg p-4 bg-white">
                        <h4 class="font-bold text-lg mb-2 text-center">أفضل المواد</h4>
                        @php
                        $subjectAverages = [];
                        foreach ($subjects as $subject) {
                        $total = 0;
                        $count = 0;
                        foreach ($students as $student) {
                        $mark = $student->marks->firstWhere('subject_code', $subject->code);
                        if ($mark && is_numeric($mark->mark)) {
                        $total += $mark->mark;
                        $count++;
                        }
                        }
                        if ($count > 0) {
                        $subjectAverages[$subject->code] = [
                        'name' => $subject->name_ar,
                        'avg' => $total / $count
                        ];
                        }
                        }
                        arsort($subjectAverages);
                        $topSubjects = array_slice($subjectAverages, 0, 3, true);
                        @endphp

                        @foreach ($topSubjects as $code => $subject)
                        <div class="mb-1">
                            <span class="font-semibold">{{ $loop->iteration }}. {{ $subject['name'] }}</span>
                            <span class="text-green-600">({{ round($subject['avg'], 2) }})</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- Footer for print -->
        <div id="footer" class="w-full text-center border-t border-gray-300 pt-2 text-sm">
            <div>تاريخ الطباعة: {{ date('Y-m-d') }} | الصفحة <span class="page-number"></span></div>
            <div class="flex justify-around mt-2">
                <div class="text-center w-[45%]">
                    <div>عميد الكلية</div>
                    <div class="font-semibold">أ.د. {{ $course->parent->dean_name_ar ?? 'غير محدد' }}</div>
                </div>
                <div class="text-center w-[45%]">
                    <div>مسجل الكلية</div>
                    <div class="font-semibold">{{ $course->parent->registrar_name_ar ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>
        <script>
            // ترقيم الصفحات
            document.addEventListener('DOMContentLoaded', function() {
                var pages = document.querySelectorAll('.page-number');
                for (var i = 0; i < pages.length; i++) {
                    pages[i].textContent = (i + 1);
                }
            });
        </script>
</body>

</html>
