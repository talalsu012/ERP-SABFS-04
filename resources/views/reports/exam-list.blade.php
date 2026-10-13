<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        كشف الامتحان - {{ $lecture->subject->name_ar ?? '' }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Tajawal', sans-serif;
        background: #f8fafc;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .exam-table {
        width: 100%;
        border-collapse: collapse;
    }

    .exam-table th,
    .exam-table td {
        border: 1px solid #000;
    }

    .exam-table th {
        font-weight: 700;
    }

    .student-row td {
        height: 32px;
    }

    /* الحقول القابلة للتعديل قبل الطباعة */
    .printable-input {
        width: 100%;
        border: 0;
        outline: none;
        background: transparent;
        padding: 5px 7px;

        font-family: 'Tajawal', sans-serif;
        font-size: 14px;
        font-weight: 500;

        color: #000;
        text-align: right;
    }

    .printable-input:hover {
        background-color: #f8fafc;
    }

    .printable-input:focus {
        background-color: #eef2ff;
        outline: 1px dashed #6366f1;
    }

    /* إعدادات صفحة الطباعة */
    @page {
        size: A4 portrait;
        margin: 8mm;
    }

    @media print {

        body {
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .print-container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        .exam-table {
            page-break-inside: auto;
        }

        .student-row {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        .printable-input {
            border: 0 !important;
            outline: none !important;
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;

            color: #000 !important;
            font-size: 14px !important;

            box-shadow: none !important;

            -webkit-appearance: none;
            appearance: none;
        }

        .printable-input::placeholder {
            color: transparent !important;
            opacity: 0 !important;
        }
    }
</style>

</head>


<body>

    <div class="print-container
           max-w-4xl mx-auto
           my-6 bg-white
           shadow-lg p-3">

        {{-- ========================= --}}
        {{-- Header --}}
        {{-- ========================= --}}

      <table class="exam-table text-sm">

    {{-- اسم الأكاديمية --}}
    <tr>
        <td colspan="4" class="text-center font-bold py-2">
            اكاديمية السودان للعلوم المصرفية والمالية
        </td>
    </tr>

    {{-- عنوان الامتحان --}}
    <tr>
        <td colspan="4" class="text-center font-bold py-2">
            إمتحانات الدور الأول للعام
            {{ $batchName }}
        </td>
    </tr>


    {{-- البرنامج + الفصل الدراسي --}}
    <tr>

        <td class="font-bold p-2" width="15%">
            البرنامج:
        </td>

        <td class="p-2" width="35%">
            {{
                $lecture->subject->faculty->name
                ?? $lecture->subject->faculty->name_ar
                ?? 'غير محدد'
            }}
        </td>


        <td class="font-bold p-2" width="15%">
            الفصل الدراسي:
        </td>

        <td class="p-1" width="35%">

            <input
                type="text"
                value="{{
                    $lecture->subject->semester_id
                        ? \App\Enums\Semester::from(
                            $lecture->subject->semester_id
                        )->getLabel()
                        : ''
                }}"
                placeholder="اكتب الفصل الدراسي"
                class="printable-input"
            >

        </td>

    </tr>


    {{-- السنة + التاريخ --}}
    <tr>

        <td class="font-bold p-2">
            السنة:
        </td>

        <td class="p-2">
            {{ $levelName }}
        </td>


        <td class="font-bold p-2">
            التاريخ:
        </td>

        <td class="p-1">

            <input
                type="text"
                value=""
                placeholder="مثال: 04/05/2026"
                class="printable-input"
            >

        </td>

    </tr>


    {{-- المقرر + الأستاذ --}}
    <tr>

        <td class="font-bold p-2">
            المقرر:
        </td>

        <td class="p-1">

            <input
                type="text"
                value="{{ $lecture->subject->name_ar ?? '' }}"
                placeholder="اكتب اسم المقرر"
                class="printable-input"
            >

        </td>


        <td class="font-bold p-2">
            الأستاذ:
        </td>

        <td class="p-1">

            <input
                type="text"
                value="{{ $lecture->staff->name ?? '' }}"
                placeholder="اكتب اسم الأستاذ"
                class="printable-input"
            >

        </td>

    </tr>


    {{-- المركز + القاعة --}}
    <tr>

        <td class="font-bold p-2">
            المركز:
        </td>

        <td class="p-1">

            <input
                type="text"
                value="الخرطوم"
                placeholder="اكتب المركز"
                class="printable-input"
            >

        </td>


        <td class="font-bold p-2">
            القاعة:
        </td>

        <td class="p-1">

            <input
                type="text"
                value="{{ $lecture->room->name ?? '' }}"
                placeholder="اكتب اسم القاعة"
                class="printable-input"
            >

        </td>

    </tr>

</table>
                </td>

            </tr>

        </table>


        {{-- ========================= --}}
        {{-- Students --}}
        {{-- ========================= --}}

        <table class="exam-table
               text-sm mt-3">

            <thead>

                <tr>

                    <th class="py-2 text-center" width="6%">
                        م
                    </th>

                    <th class="py-2 text-center" width="18%">
                        الرقم الجامعي
                    </th>

                    <th class="py-2 text-center" width="36%">
                        اسم الطالب
                    </th>

                    <th class="py-2 text-center" width="14%">
                        التوقيع
                    </th>

                    <th class="py-2 text-center" width="12%">
                        الدرجة
                    </th>

                    <th class="py-2 text-center" width="14%">
                        ملاحظات
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($students as $index => $student)

                    <tr class="student-row">

                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>

                        <td class="text-center font-medium" dir="ltr">
                            {{ $student->university_no }}
                        </td>

                        <td class="px-2">
                            {{ $student->name_ar }}
                        </td>

                        {{-- التوقيع --}}
                        <td></td>

                        {{-- الدرجة --}}
                        <td></td>

                        {{-- ملاحظات --}}
                        <td></td>

                    </tr>

                @endforeach


                {{--
                إكمال الكشف إلى 26 سطراً
                مثل نموذج PDF
                --}}

                @for(
                        $i = $students->count() + 1;
                        $i <= 26;
                        $i++
                    )

                    <tr class="student-row">

                        <td class="text-center">
                            {{ $i }}
                        </td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                        <td></td>

                    </tr>

                @endfor

            </tbody>

        </table>


        {{-- ========================= --}}
        {{-- Footer --}}
        {{-- ========================= --}}

    

    </div>


    {{-- Print Button --}}

    <div class="fixed bottom-6 left-6 no-print">

        <button onclick="window.print()" class="
            bg-indigo-600
            hover:bg-indigo-700
            text-white
            font-medium
            py-2 px-5
            rounded-lg
            shadow-lg
            flex items-center
            gap-2
        ">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4
                   a2 2 0 00-2-2H5a2 2 0 00-2 2v4
                   a2 2 0 002 2h2m2 4h6
                   a2 2 0 002-2v-4
                   a2 2 0 00-2-2H9
                   a2 2 0 00-2 2v4
                   a2 2 0 002 2zm8-12V5
                   a2 2 0 00-2-2H9
                   a2 2 0 00-2 2v4h10z" />

            </svg>

            طباعة كشف الامتحان

        </button>

    </div>

</body>

</html>