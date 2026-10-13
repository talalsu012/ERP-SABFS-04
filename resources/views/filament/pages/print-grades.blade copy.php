<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف الدرجات - {{ $course->name_ar }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .vertical-text {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }

            .print-header {
                position: fixed;
                top: 0;
                width: 100%;
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }

            .print-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                border-top: 1px solid #333;
                padding-top: 5px;
                font-size: 12px;
            }

            .print-content {
                margin-top: 50px;
                margin-bottom: 50px;
            }

            .no-print {
                display: none;
            }
        }
    </style>

</head>

<body class="bg-gray-50 font-sans">
<div class="container mx-auto w-[95%] py-8">
@php
    $header = function() use ($course, $students) {
        return view('filament.components.print-header', [
            'title' => $course->name_ar,
            'subtitle' => "كشف درجات الطلاب - الدفعة {$course->batches->first()->batch_start}",
            'level' => $students->first()->level->name_ar ?? 'غير محدد', // Now getting level from student
            'college' => $course->parent->name_ar ?? 'غير محدد'
        ]);
    };

        $footer = function() use ($course) {
            return view('filament.components.print-footer', [
                'dean' => $course->parent->dean_name_ar ?? 'غير محدد',
                'registrar' => $course->parent->registrar_name_ar ?? 'غير محدد'
            ]);
        };
    @endphp

    <div class="print-container">
        <!-- Header will repeat on each printed page -->
        <div class="print-header">
            {{ $header() }}
        </div>


        <!-- Main content -->
        <div class="print-content">
            <!-- Grades Table -->
            <div class="my-6 overflow-x-auto">
                <table class="w-full border border-gray-300">
                    <thead>
                        <tr>
                            <th rowspan="2" class="border bg-gray-100 p-2">#</th>
                            <th rowspan="2" class="border bg-gray-100 p-2">الرقم الجامعي</th>
                            <th rowspan="2" class="border bg-gray-100 p-2">اسم الطالب</th>
                            @foreach ($subjects as $subject)
                                <th class="border bg-gray-100 p-1 vertical-text h-32">{{ $subject->name_ar }}</th>
                            @endforeach
                            <th rowspan="2" class="border bg-gray-100 p-2">المعدل</th>
                        </tr>
                        <tr>
                            @foreach ($subjects as $subject)
                                <th class="border bg-gray-300 p-1 h-12">{{ $subject->crch }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($students as $index => $student)
                            @php
                                $studentMarks = $student->marks->keyBy('subject_code');
                                $averages = $student->marks->filter()->avg('mark');
                                $averageGrade = $grades->first(fn($g) => $averages >= $g->lower && $averages <= $g->upper);
                            @endphp
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                <td class="border p-2 text-center">{{ $student->university_no }}</td>
                                <td class="border p-2">{{ $student->name_ar }}</td>

                                @foreach ($subjects as $subject)
                                    @php
                                        $mark = $studentMarks->get($subject->code);
                                        $grade = $mark ? $grades->first(fn($g) => $mark->mark >= $g->lower && $mark->mark <= $g->upper) : null;
                                    @endphp
                                    <td class="border p-2 text-center {{ $mark && $mark->mark < 50 ? 'bg-gray-200' : '' }}">
                                        {{ $mark?->mark ?? '-' }}
                                        @if ($grade)
                                            <span class="text-xs">({{ $grade->titlear }})</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="border p-2 text-center font-semibold">
                                    {{ $averages ? round($averages, 2) : '-' }}
                                    @if ($averageGrade)
                                        <span class="text-xs">({{ $averageGrade->titlear }})</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Subject Analysis -->
            <div class="my-8">
                <h3 class="text-xl font-bold mb-4 text-center">تحليل نتائج المواد</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($subjects as $subject)
                        @php $stats = $subjectStats[$subject->code] @endphp
                        <div class="border rounded-lg p-4 bg-white shadow-sm">
                            <h4 class="font-bold text-lg mb-2">{{ $subject->name_ar }} ({{ $subject->code }})</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <div>عدد الطلاب:</div>
                                <div class="font-semibold">{{ $stats['total'] }}</div>

                                <div class="text-green-600">الناجحون:</div>
                                <div class="font-semibold text-green-600">{{ $stats['passed'] }}</div>

                                <div class="text-red-600">الراسبون:</div>
                                <div class="font-semibold text-red-600">{{ $stats['failed'] }}</div>

                                <div>نسبة النجاح:</div>
                                <div class="font-semibold">
                                    {{ $stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100, 2) : 0 }}%
                                </div>

                                <div>متوسط الدرجات:</div>
                                <div class="font-semibold">{{ $stats['average'] }}</div>

                                <div>أعلى درجة:</div>
                                <div class="font-semibold">{{ $stats['max'] }}</div>

                                <div>أقل درجة:</div>
                                <div class="font-semibold">{{ $stats['min'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Overall Summary -->
            <div class="border-t-2 border-gray-300 pt-6">
                <h3 class="text-xl font-bold mb-4 text-center">ملخص عام للنتائج</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Student Stats -->
                    <div class="border rounded-lg p-4 bg-white shadow-sm">
                        <h4 class="font-bold text-lg mb-2 text-center">إحصائيات الطلاب</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div>عدد الطلاب:</div>
                            <div class="font-semibold">{{ $students->count() }}</div>

                            @php
                                $allPassed = $students->filter(function($student) use ($subjects) {
                                    return $subjects->every(function($subject) use ($student) {
                                        $mark = $student->marks->firstWhere('subject_code', $subject->code);
                                        return $mark && $mark->mark >= 50;
                                    });
                                })->count();
                            @endphp

                            <div class="text-green-600">الناجحون في جميع المواد:</div>
                            <div class="font-semibold text-green-600">{{ $allPassed }}</div>

                            <div class="text-red-600">الراسبون في مادة واحدة على الأقل:</div>
                            <div class="font-semibold text-red-600">{{ $students->count() - $allPassed }}</div>
                        </div>
                    </div>

                    <!-- Top Students -->
                    <div class="border rounded-lg p-4 bg-white shadow-sm">
                        <h4 class="font-bold text-lg mb-2 text-center">أفضل الطلاب</h4>
                        @php
                            $topStudents = $students->map(function($student) use ($subjects) {
                                $validMarks = $student->marks->filter(fn($m) => $m->mark !== null);
                                return [
                                    'name' => $student->name_ar,
                                    'avg' => $validMarks->avg('mark')
                                ];
                            })->sortByDesc('avg')->take(3);
                        @endphp

                        @foreach ($topStudents as $student)
                            <div class="mb-1">
                                <span class="font-semibold">{{ $loop->iteration }}. {{ $student['name'] }}</span>
                                <span class="text-blue-600">({{ round($student['avg'], 2) }})</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Top Subjects -->
                    <div class="border rounded-lg p-4 bg-white shadow-sm">
                        <h4 class="font-bold text-lg mb-2 text-center">أفضل المواد</h4>
                        @php
                            $topSubjects = collect($subjectStats)
                                ->map(function($stats, $code) use ($subjects) {
                                    $subject = $subjects->firstWhere('code', $code);
                                    return [
                                        'name' => $subject->name_ar,
                                        'avg' => $stats['average']
                                    ];
                                })
                                ->sortByDesc('avg')
                                ->take(3);
                        @endphp

                        @foreach ($topSubjects as $subject)
                            <div class="mb-1">
                                <span class="font-semibold">{{ $loop->iteration }}. {{ $subject['name'] }}</span>
                                <span class="text-green-600">({{ $subject['avg'] }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer will repeat on each printed page -->
        <div class="print-footer">

        </div>
    </div>
</div>
</body>


</html>
