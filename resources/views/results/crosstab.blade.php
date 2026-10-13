<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة النتائج - {{ $batch->name_ar }}</title>
    <style>
        body {
            font-family: 'Arial Arabic', 'Times New Roman', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 18px;
            color: #555;
        }
        .filters {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .filters span {
            font-weight: bold;
            margin-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-warning {
            color: #ffc107;
        }
        .summary-table {
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 8px;
        }
        .no-print {
            display: none;
        }
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>جامعة {{ config('app.name') }}</h1>
        <h2>تقرير نتائج الطلاب - دفعة {{ $batch->name_ar }}</h2>
        <div class="filters">
            @if($filters['level'])
                <span>المستوى:</span> {{ $levels->firstWhere('code', $filters['level'])->name_ar ?? $filters['level'] }}
            @endif
            @if($filters['semester'])
                <span>الفصل:</span> {{ $filters['semester'] == '1' ? 'الأول' : 'الثاني' }}
            @endif
            @if($filters['exam_type'])
                <span>نوع الامتحان:</span> {{ $filters['exam_type'] == '1' ? 'أساسي' : 'مطلق' }}
            @endif
            @if($filters['faculty'])
                <span>الكلية:</span> {{ $faculties->firstWhere('code', $filters['faculty'])->name_ar ?? $filters['faculty'] }}
            @endif
        </div>
        <div class="filters">
            <span>تاريخ الطباعة:</span> {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <!-- Subject Statistics -->
    <table class="summary-table">
        <thead>
            <tr>
                <th rowspan="2">المادة</th>
                <th colspan="3">الدرجات</th>
                <th colspan="3">الطلاب</th>
                <th rowspan="2">نسبة النجاح</th>
            </tr>
            <tr>
                <th>المعدل</th>
                <th>الأعلى</th>
                <th>الأدنى</th>
                <th class="text-success">الناجحون</th>
                <th class="text-danger">الراسبون</th>
                <th class="text-warning">الغائبون</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
            @php $stat = $subjectStats[$subject->code] @endphp
            <tr>
                <td>{{ $subject->name_ar }} ({{ $subject->code }})</td>
                <td>{{ $stat['average'] }}</td>
                <td>{{ $stat['max'] }}</td>
                <td>{{ $stat['min'] }}</td>
                <td class="text-success">{{ $stat['passed'] }}</td>
                <td class="text-danger">{{ $stat['failed'] }}</td>
                <td class="text-warning">{{ $stat['absent'] }}</td>
                <td>{{ $stat['pass_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Student Results -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">الرقم الجامعي</th>
                <th rowspan="2">اسم الطالب</th>
                <th rowspan="2">الكلية</th>
                <th rowspan="2">المستوى</th>
                @foreach($subjects as $subject)
                <th>{{ $subject->code }}</th>
                @endforeach
                <th rowspan="2">المعدل</th>
                <th rowspan="2">التقدير</th>
            </tr>
            <tr>
                @foreach($subjects as $subject)
                <th>{{ $subject->crch }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->university_no }}</td>
                <td>{{ $student->name_ar }}</td>
                <td>{{ $student->faculty->name_ar ?? '-' }}</td>
                <td>{{ $student->level->name_ar ?? '-' }}</td>

                @foreach($subjects as $subject)
                @php
                    $mark = $student->marks->firstWhere('subject_code', $subject->code);
                    $display = '-';

                    if ($mark) {
                        if ($mark->reson_code && $mark->reson_code > 2000) {
                            $display = $mark->reason->code ?? 'غ';
                        } else {
                            $display = $mark->mark;
                        }
                    }
                @endphp
                <td>{{ $display }}</td>
                @endforeach

                @php
                    $recommendation = $student->recommendations->first();
                    $gpa = $recommendation ? $recommendation->gpa : null;
                    $gpaGrade = $gpa ? $this->getGpaGrade($gpa) : '-';
                @endphp
                <td>{{ $gpa ? number_format($gpa, 2) : '-' }}</td>
                <td>{{ $gpaGrade }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: left; font-size: 14px;">
        <p><strong>ملاحظات:</strong></p>
        <ul>
            <li>الرموز التي تبدأ ب 2 تعني أن الطالب غائب</li>
            <li>الدرجات أقل من 50 تعني أن الطالب راسب في المادة</li>
        </ul>
    </div>
</body>
</html>
