<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف درجات الطلاب - {{ $course->name_ar }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .print-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 120px;
            }
            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 80px;
            }
            .print-content {
                margin-top: 130px;
                margin-bottom: 90px;
            }
            .page-break {
                page-break-after: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
 <!-- الترويسة الثابتة -->
 <div class="print-header bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex justify-between items-center">
    <div class="w-1/4 text-center">
        <img src="{{ asset('images/college-logo.png') }}" alt="شعار الكلية" class="h-16 mx-auto">
    </div>
    <div class="w-2/4 text-center">
        <h1 class="text-xl font-bold text-gray-800">جامعة العلوم التطبيقية</h1>
        <h2 class="text-lg text-gray-600">كلية {{ $course->parent->name_ar ?? 'غير محدد' }}</h2>
        <h3 class="text-md text-gray-500">{{ $course->name_ar }}</h3>
        <p class="text-sm text-gray-500">{{ "كشف درجات الطلاب - الدفعة " . ($course->batches->first()->batch_start ?? 'غير محدد') }}</p>
        <p class="text-sm text-gray-500">المستوى: {{ $students->first()->level->name_ar ?? 'غير محدد' }}</p>
    </div>
    <div class="w-1/4 text-left">
        <p class="text-sm text-gray-600">تاريخ الطباعة: {{ date('Y-m-d') }}</p>
    </div>
</div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="print-content px-6 py-4">
        <!-- جدول الدرجات الرئيسي -->
        <div class="mb-8 avoid-break">
            <h3 class="text-lg font-semibold mb-3 text-center bg-blue-100 py-2 rounded">كشف الدرجات التفصيلي</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 border text-center">#</th>
                            <th class="py-2 px-3 border text-center">الرقم الجامعي</th>
                            <th class="py-2 px-3 border text-center">اسم الطالب</th>
                            <th class="py-2 px-3 border text-center">المستوى</th>
                            <th class="py-2 px-3 border text-center">الدفعة</th>
                            @foreach($subjects as $subject)
                            <th class="py-2 px-3 border text-center">
                                {{ $subject->name_ar }}<br>
                                <span class="text-xs text-gray-500">{{ $subject->code }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="py-2 px-3 border text-center">{{ $loop->iteration }}</td>
                            <td class="py-2 px-3 border text-center">{{ $student->university_no }}</td>
                            <td class="py-2 px-3 border">{{ $student->name_ar }}</td>
                            <td class="py-2 px-3 border text-center">{{ $student->level->name_ar ?? '' }}</td>
                            <td class="py-2 px-3 border text-center">{{ $student->batch->name_ar ?? '' }}</td>
                            @foreach($subjects as $subject)
                                @php
                                    $mark = $student->marks->firstWhere('subject_code', $subject->code);
                                    $grade = $mark ? APP\Models\Result\Grade::getGradeTitle($mark->mark) : null;
                                @endphp
                                <td class="py-2 px-3 border text-center
                                    @if($grade && $grade->upper >= 50) text-green-600 font-medium
                                    @elseif($grade) text-red-600 font-medium
                                    @endif">
                                    @if($mark)
                                        {{ $mark->mark }} ({{ $grade->title ?? '--' }})
                                    @else
                                        --
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- صفحة التحليلات الإحصائية -->
        <div class="page-break"></div>
        <div class="avoid-break">
            <h3 class="text-lg font-semibold mb-4 text-center bg-blue-100 py-2 rounded">التحليل الإحصائي للنتائج</h3>

            <!-- إحصائيات عامة -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600 mb-1">عدد الطلاب</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ count($students) }}</p>
                </div>

                @php
                    $passedStudents = 0;
                    foreach($students as $student) {
                        $allPassed = true;
                        foreach($subjects as $subject) {
                            $mark = $student->marks->firstWhere('subject_code', $subject->code);
                            if($mark && $mark->mark < 50) {
                                $allPassed = false;
                                break;
                            }
                        }
                        if($allPassed) $passedStudents++;
                    }
                @endphp

                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600 mb-1">عدد الناجحين</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $passedStudents }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ round(($passedStudents / count($students)) * 100, 2) }}%</p>
                </div>

                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600 mb-1">عدد الراسبين</h4>
                    <p class="text-2xl font-bold text-red-600">{{ count($students) - $passedStudents }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ round(((count($students) - $passedStudents) / count($students)) * 100, 2) }}%</p>
                </div>

                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <h4 class="text-sm font-medium text-gray-600 mb-1">متوسط النجاح</h4>
                    <p class="text-2xl font-bold text-purple-600">
                        @php
                            $totalSubjects = count($subjects);
                            $passedSubjects = 0;
                            foreach($subjectStats as $stat) {
                                $passedSubjects += $stat['passed'];
                            }
                            echo round(($passedSubjects / ($totalSubjects * count($students))) * 100, 2);
                        @endphp
                        %
                    </p>
                </div>
            </div>

            <!-- إحصائيات كل مادة -->
            <h4 class="text-md font-semibold mb-3 border-b pb-2">إحصائيات المواد الدراسية</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @foreach($subjects as $subject)
                @php
                    $stats = $subjectStats[$subject->code] ?? [];
                    $gradeDistribution = [];
                    $gradeColors = [];

                    foreach($grades as $grade) {
                        $count = 0;
                        foreach($students as $student) {
                            $mark = $student->marks->firstWhere('subject_code', $subject->code);
                            if($mark && $mark->mark >= $grade->lower && $mark->mark <= $grade->upper) {
                                $count++;
                            }
                        }
                        $gradeDistribution[$grade->title] = $count;
                        $gradeColors[$grade->title] = $grade->upper >= 50 ? '#28a745' : '#dc3545';
                    }
                @endphp

                <div class="bg-white p-4 rounded border border-gray-200 shadow-sm avoid-break">
                    <h5 class="font-medium text-center mb-3 bg-gray-100 py-1 rounded">
                        {{ $subject->name_ar }} ({{ $subject->code }})
                    </h5>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <table class="min-w-full">
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">عدد الطلاب:</td>
                                    <td class="py-1 text-sm font-medium">{{ $stats['total'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">الناجحون:</td>
                                    <td class="py-1 text-sm font-medium text-green-600">{{ $stats['passed'] ?? 0 }} ({{ $stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100, 2) : 0 }}%)</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">الراسبون:</td>
                                    <td class="py-1 text-sm font-medium text-red-600">{{ $stats['failed'] ?? 0 }} ({{ $stats['total'] > 0 ? round(($stats['failed'] / $stats['total']) * 100, 2) : 0 }}%)</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">أعلى درجة:</td>
                                    <td class="py-1 text-sm font-medium">{{ $stats['max'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">أقل درجة:</td>
                                    <td class="py-1 text-sm font-medium">{{ $stats['min'] ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-sm text-gray-600">المتوسط:</td>
                                    <td class="py-1 text-sm font-medium">{{ $stats['average'] ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <canvas id="chart-{{ $subject->code }}" height="200"></canvas>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    new Chart(document.getElementById('chart-{{ $subject->code }}'), {
                                        type: 'doughnut',
                                        data: {
                                            labels: {!! json_encode(array_keys($gradeDistribution)) !!},
                                            datasets: [{
                                                data: {!! json_encode(array_values($gradeDistribution)) !!},
                                                backgroundColor: {!! json_encode(array_values($gradeColors)) !!},
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'bottom',
                                                    rtl: true
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-sm font-medium text-center mb-2">توزيع الدرجات</h6>
                        <table class="min-w-full border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-1 px-2 border text-center text-xs">التقدير</th>
                                    <th class="py-1 px-2 border text-center text-xs">العدد</th>
                                    <th class="py-1 px-2 border text-center text-xs">النسبة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeDistribution as $grade => $count)
                                <tr>
                                    <td class="py-1 px-2 border text-center text-xs">{{ $grade }}</td>
                                    <td class="py-1 px-2 border text-center text-xs">{{ $count }}</td>
                                    <td class="py-1 px-2 border text-center text-xs">
                                        {{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100, 2) : 0 }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- الفوتر الثابت -->
    <div class="print-header bg-white border-b border-gray-200 px-6 py-4">
        @include('filament.components.print-footer', [
            'title' => $course->name_ar,
            'subtitle' => "كشف درجات الطلاب - الدفعة " . ($course->batches->first()->batch_start ?? 'غير محدد'),
            'level' => $students->first()->level->name_ar ?? 'غير محدد',
            'college' => $course->parent->name_ar ?? 'غير محدد'
        ])
    </div>

    <!-- <script>
        // طباعة الصفحة تلقائياً عند التحميل
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script> -->
</body>
</html>
