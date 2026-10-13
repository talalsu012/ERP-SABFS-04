<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الطلاب</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="container mx-auto px-4 py-8">
        <!-- عنوان التقرير -->
              <!-- Report Header -->
              <div class="bg-white rounded-lg shadow-md p-6 mb-6 header-border">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full md:w-1/4 flex justify-center">
                    <img src="http://localhost:8000/static/academy_logo.png" alt="شعار الكلية" class="h-20 object-contain">
                </div>
                <div class="w-full md:w-2/4 text-center space-y-2">
                    <p class="text-lg text-indigo-600">بسم الله الرحمن الرحيم</p>
                    <h1 class="text-2xl font-bold text-gray-800">اكاديمية السودان للعلوم المصرفية والمالية</h1>
                    <h2 class="text-xl font-semibold text-indigo-700">كشف طلاب {{ $grouping->name }}</h2>

                </div>
                <div class="w-full md:w-1/4 text-left md:text-right">
                    <div class="bg-gray-100 p-2 rounded-lg inline-block">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">تاريخ الطباعة:</span>
                            {{ now()->translatedFormat('Y-m-d') }}
                        </p>
                    </div>
                </div>
            </div>


        </div>


        <!-- بطاقة المجموعة -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 border-l-4 border-indigo-500">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">معلومات المجموعة</h2>
                    <div class="mt-4 space-y-2">
                        <p class="text-gray-700"><span class="font-medium">اسم المجموعة:</span> {{ $grouping->name }}</p>
                        @if($grouping->parent)
                            <p class="text-gray-700"><span class="font-medium">المجموعة الأم:</span> {{ $grouping->parent->name }}</p>
                        @endif
                        @if($grouping->room)
                            <p class="text-gray-700"><span class="font-medium">القاعة:</span> {{ $grouping->room->name }}</p>
                            <p class="text-gray-700"><span class="font-medium">السعة:</span> {{ $grouping->room->capacity }}</p>
                        @endif
                        @if($grouping->campus)
                            <p class="text-gray-700"><span class="font-medium">الكلية:</span> {{ $grouping->campus->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-lg">
                    <span class="font-bold">عدد الطلاب:</span> {{ $grouping->students->count() }}
                </div>
            </div>
        </div>

        <!-- جدول الطلاب -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرقم الجامعي</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الطالب</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدفعة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($grouping->students as $index => $student)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">{{ $student->university_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->name_ar ?? $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->batch->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->code ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    نشط
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ملخص التقرير -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-indigo-500">
                <h3 class="font-medium text-gray-700">إجمالي الطلاب</h3>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $grouping->students->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-green-500">
                <h3 class="font-medium text-gray-700">القاعة</h3>
                <p class="text-xl font-bold text-green-600 mt-2">{{ $grouping->room->name ?? 'غير محدد' }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-t-4 border-blue-500">
                <h3 class="font-medium text-gray-700">الكلية</h3>
                <p class="text-xl font-bold text-blue-600 mt-2">{{  $student->faculty->name ?? 'غير محدد' }}</p>
            </div>
        </div>

        <!-- تذييل الصفحة -->
        <div class="mt-12 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>تم إنشاء التقرير بواسطة نظام الحضور والانصراف</p>
            <p class="mt-1">{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
