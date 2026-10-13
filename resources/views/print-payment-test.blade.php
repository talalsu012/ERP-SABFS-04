<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-content {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow print:shadow-none">
        <!-- University Header -->
        <div class="text-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
            <p class="text-gray-600">Official Payment Receipt</p>
        </div>

        <!-- Payment Details -->
        <div class="grid grid-cols-2 gap-4 mb-6">

            <table class="w-full">
                <tbody>
                    <tr class="border-t border-gray-200">
                        <td class="text-right p-3 width-50% ">{{ __('reg.faculty') }}</td>
                        <td class="text-right p-3">: {{ !empty($record->student->faculty->name) ? $record->student->faculty->name : '-' }}
                        </td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="text-right p-3 width-50% ">{{ __('reg.level') }}</td>
                        <td class="text-right p-3">
                            : {{ !empty($record->level->name) ? $record->level->name : $record->student->level->name }}
                            ({{ !empty($record->batch->name) ? $record->batch->name : $record->bankdate }})</td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="text-right p-3 width-50% ">{{ __('reg.name_ar') }}</td>
                        <td class="text-right p-3">: {{ $record->student->name_ar }}</td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="p-3">{{ __('reg.voucher_no') }}:</td>
                        <td class="text-right p-3">{{ $record->voucher_no }}</td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="p-3">{{ __('reg.bankdate') }}</td>
                        <td class="text-right p-3">{{ $record->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr class="border-t border-gray-200">
                        <td class="p-3">{{ __('reg.university_no') }}</td>
                        <td class="text-right p-3">{{ $record->university_no }}</td>
                    </tr>

                </tbody>
            </table>
        </div>


        <!-- Payment Breakdown -->
        <div class="border border-gray-200 rounded mb-6">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left p-3">{{ __('reg.description') }}</th>
                        <th class="text-right p-3">{{ __('reg.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-200">
                        <td class="p-3">{{ $record->sys_type ?? 'General Payment' }}</td>
                        <td class="text-right p-3">{{ number_format($record->amount, 2) }} {{ $record->currency }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-4 text-center text-sm text-gray-500">
            <p class="mt-1">For inquiries, contact: finance@sabfs.edu</p>
        </div>
    </div>
</body>

</html>
