{{
    dd($dataForm['faculty'])
}}
@extends('layouts.app')

@section('title', 'تقرير نتائج الطلاب')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-0">أكاديمية السودان للعلوم المصرفية والمالية</h4>
                    <h5 class="mb-0">أمانة الشؤون العلمية</h5>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="mb-0">نتيجة امتحان العام الدراسي {{ now()->year }}</h4>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- معلومات التقرير -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <p><strong>البرنامج:</strong> بكالريوس الشرف في تقانة المعلومات</p>
                </div>
                <div class="col-md-4">
                    <p><strong>الفرقة:</strong> الثانية</p>
                </div>
                <div class="col-md-4">
                    <p><strong>الدور:</strong> الأول</p>
                </div>
                <div class="col-md-4">
                    <p><strong>الفصل الدراسي:</strong> الأول والثاني</p>
                </div>
                <div class="col-md-4">
                    <p><strong>الدفعة:</strong> {{ $batch }}</p>
                </div>
            </div>

            <!-- جدول النتائج -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="bg-light">
                        <tr>
                            <th rowspan="2">م</th>
                            <th rowspan="2">رقم الطالب</th>
                            <th rowspan="2">اسم الطالب</th>
                            @foreach($subjects as $subject)
                                <th>{{ $subject->name_ar ?? 'N/A' }}</th>
                            @endforeach
                            <th rowspan="2">المجموع</th>
                            <th rowspan="2">التقدير</th>
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                                <th>الدرجة</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->university_no }}</td>
                            <td class="text-right">{{ $student->name_ar }}</td>

                            @foreach($subjects as $subject)
                                @php
                                    $mark = $student->marks->firstWhere('subject_code', $subject->code);
                                @endphp
                                <td>{{ $mark ? $mark->mark : '-' }}</td>
                            @endforeach

                            <td>{{ $student->recom ? $student->recom->sumMarks : '0' }}</td>
                            <td>{{ $student->recom ? $student->recom->grade : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-4">
                    <p>أمين الشؤون العلمية</p>
                    <p>التوقيع: _________________</p>
                </div>
                <div class="col-md-4">
                    <p>ضابط الامتحانات</p>
                    <p>التوقيع: _________________</p>
                </div>
                <div class="col-md-4">
                    <p>أعضاء اللجنة</p>
                    <p>1. _________________</p>
                    <p>2. _________________</p>
                    <p>3. _________________</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> طباعة التقرير
        </button>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    table {
        direction: rtl;
    }
    .text-right {
        text-align: right !important;
    }
</style>
@endsection
