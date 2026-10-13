<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف تفصيلي للدرجات - {{ $course->name_ar }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 15px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h2 { margin: 5px 0; font-size: 20px; }
        .header h3 { margin: 5px 0; font-size: 16px; font-weight: 400; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th { background-color: #f2f2f2; font-weight: 700; }
        .student-name { text-align: right; }
        tfoot th, tfoot td { font-weight: 700; background-color: #fafafa; }
        @media print {
            body { margin: 5mm; font-size: 9pt; }
            .no-print { display: none; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>الجامعة الإسلامية</h1>
        <h2>{{ $course->name_ar }}</h2>
        <h3>كشف تفصيلي لدرجات المستوى: {{ $levelCode }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">م</th>
                <th rowspan="2" style="width: 8%;">الرقم الجامعي</th>
                <th rowspan="2" style="width: 22%;">اسم الطالب</th>
                @foreach ($subjects as $subject)
                    <th>{{ $subject->name_ar }}</th>
                @endforeach
                <th rowspan="2">المجموع</th>
                <th rowspan="2">المعدل</th>
                <th rowspan="2">التقدير</th>
            </tr>
            <tr>
                @foreach ($subjects as $subject)
                    <th>({{ $subject->exam ?? 100 }})</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->university_no }}</td>
                    <td class="student-name">{{ $student->name_ar }}</td>

                    @foreach ($subjects as $subject)
                        <td>{{ $student->pivoted_marks[$subject->code]->mark ?? '-' }}</td>
                    @endforeach

                    <td><b>{{ $student->recom->sumMarks ?? '-' }}</b></td>
                    <td>{{ $student->recom->gpa ?? '-' }}</td>
                    <td>{{ $student->recom->recom_code ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + $subjects->count() }}">لم يتم العثور على بيانات طلاب مسجلين.</td>
                </tr>
            @endforelse
        </tbody>
        @if($students->isNotEmpty())
        <tfoot>
            <tr>
                <th colspan="3">عدد الناجحين</th>
                @foreach($subjects as $subject)
                    <td>{{ $subjectStats[$subject->code]['passed'] }}</td>
                @endforeach
                <td colspan="3"></td>
            </tr>
            <tr>
                <th colspan="3">عدد الراسبين</th>
                @foreach($subjects as $subject)
                    <td>{{ $subjectStats[$subject->code]['failed'] }}</td>
                @endforeach
                <td colspan="3"></td>
            </tr>
            <tr>
                <th colspan="3">الإجمالي</th>
                @foreach($subjects as $subject)
                    <td><b>{{ $subjectStats[$subject->code]['total'] }}</b></td>
                @endforeach
                <td colspan="3"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

</body>
</html>
