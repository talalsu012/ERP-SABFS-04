<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير ملخص النتائج</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-6 max-w-7xl">
        {{-- بطاقة التقرير الرئيسية --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            {{-- Header with gradient --}}
            <div class="gradient-bg px-6 py-4 text-white">
                <div class="text-center">
                    <h1 class="text-2xl font-bold mb-2">تقرير ملخص النتائج</h1>
                    <div class="flex justify-center gap-4 text-xs opacity-90">
                        <span>{{ $faculty->name_ar ?? $faculty->name }}</span>
                        <span>|</span>
                        <span>المستوى: {{ $level->name_ar ?? $level->name }}</span>
                        <span>|</span>
                        <span>الدفعة: {{ $batch }}</span>
                    </div>
                </div>
            </div>

            {{-- المحتوى --}}
            <div class="p-6">
                
                {{-- الإحصائيات الرئيسية - بطاقات مصغرة --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 text-center">
                        <div class="inline-flex p-2 bg-blue-500 rounded-full text-white mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-xs mb-1">العدد الكلي للطلاب</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $statistics['totalStudents'] }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 text-center">
                        <div class="inline-flex p-2 bg-green-500 rounded-full text-white mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-xs mb-1">الطلاب الجالسين فعلياً</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statistics['actualSittingCount'] }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 text-center">
                        <div class="inline-flex p-2 bg-purple-500 rounded-full text-white mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-xs mb-1">نسبة النجاح من الجالسين</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $statistics['passPercentage'] }}%</p>
                    </div>
                </div>

                {{-- إحصاء التوصيات مع رسم بياني خطي --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-6 w-1 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-gray-800">إحصاء التوصيات</h2>
                    </div>
                    
                    {{-- رسم بياني خطي للتوصيات --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                        <canvas id="recommendationsChart" class="max-h-96 w-full"></canvas>
                    </div>
                    
                    {{-- بطاقات التوصيات المصغرة --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                        @foreach($statistics['recommendationStats'] as $label => $count)
                            @php
                                $colors = [
                                    'ممتاز' => ['bg' => 'bg-green-50', 'border' => 'border-green-500', 'text' => 'text-green-600'],
                                    'جيد جداً' => ['bg' => 'bg-green-50', 'border' => 'border-green-500', 'text' => 'text-green-600'],
                                    'جيد' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'text' => 'text-yellow-600'],
                                    'ملحق' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-500', 'text' => 'text-orange-600'],
                                    'بديل+ملحق' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-600'],
                                    'repeat_some' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-600'],
                                    'مقبول' => ['bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'text' => 'text-gray-600'],
                                    'بديل' => ['bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'text' => 'text-gray-600'],
                                ];
                                $color = $colors[$label] ?? ['bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'text' => 'text-blue-600'];
                                $arabicLabels = [
                                    'passed_all' => 'الناجحين كل المقررات',
                                    'supplementary' => 'الملاحق',
                                    'supplementary_alternative' => 'الملاحق والبدائل',
                                    'repeat_all' => 'الإعادة بكل المقررات',
                                    'repeat_some' => 'الإعادة ببعض المقررات',
                                    'admin_separation' => 'الفصل الإداري',
                                    'academic_separation' => 'الفصل الأكاديمي',
                                ];
                                $arabicLabel = $arabicLabels[$label] ?? $label;
                            @endphp
                            <div class="{{ $color['bg'] }} rounded p-2 border-r-2 {{ $color['border'] }}">
                                <p class="text-xs text-gray-600 mb-1">{{ $arabicLabel }}</p>
                                <p class="text-lg font-bold {{ $color['text'] }}">{{ $count }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- نسبة نجاح المواد --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-6 w-1 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-gray-800">نسبة نجاح المواد</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden border border-gray-200">
                            <thead class="bg-gray-50">
                                
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المادة</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الجالسين</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الناجحين</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">نسبة النجاح</th>
                                
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($statistics['subjectPassRates'] as $subject)
                                    @php
                                        $rateColor = 'text-red-600';
                                        if ($subject['pass_rate'] >= 70) $rateColor = 'text-green-600';
                                        elseif ($subject['pass_rate'] >= 50) $rateColor = 'text-yellow-600';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-2 text-sm text-gray-900 font-medium">{{ $subject['subject_name'] }}</td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-600">{{ $subject['sitting_count'] }}</td>
                                        <td class="px-4 py-2 text-sm text-center text-gray-600">{{ $subject['passed_count'] }}</td>
                                        <td class="px-4 py-2 text-sm text-center font-bold {{ $rateColor }}">{{ $subject['pass_rate'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // رسم بياني خطي للتوصيات
        const ctx = document.getElementById('recommendationsChart').getContext('2d');
        
        const recommendationData = @json($statistics['recommendationStats']);
        
        const arabicLabelsMap = {
            'passed_all': 'الناجحين كل المقررات',
            'supplementary': 'الملاحق',
            'supplementary_alternative': 'الملاحق والبدائل',
            'repeat_all': 'الإعادة بكل المقررات',
            'repeat_some': 'الإعادة ببعض المقررات',
            'admin_separation': 'الفصل الإداري',
            'academic_separation': 'الفصل الأكاديمي'
        };
        
        const labels = Object.keys(recommendationData).map(key => arabicLabelsMap[key] || key);
        const data = Object.values(recommendationData);
        
        // ألوان متدرجة للخط
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الطلاب',
                    data: data,
                    borderColor: '#3B82F6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#EF4444',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Tajawal',
                                size: 12
                            },
                            usePointStyle: true,
                            boxWidth: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                const total = data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        },
                        bodyFont: {
                            family: 'Tajawal'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'عدد الطلاب',
                            font: {
                                family: 'Tajawal',
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: 'Tajawal'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'نوع التوصية',
                            font: {
                                family: 'Tajawal',
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                family: 'Tajawal',
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>

</body>
</html>