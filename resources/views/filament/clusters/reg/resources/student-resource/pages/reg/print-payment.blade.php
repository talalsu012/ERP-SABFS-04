<x-filament::page>
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow print:shadow-none">
        <!-- University Header -->
        <div class="text-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
            <p class="text-gray-600">Official Payment Receipt</p>
        </div>

        <!-- Payment Details -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="font-semibold">Receipt Number:</p>
                <p class="text-lg">{{ $record->voucher_no }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">Date:</p>
                <p>{{ $record->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Student and Cluster Info -->
        <div class="mb-6 bg-gray-50 p-4 rounded">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold">University Number:</p>
                    <p>{{ $record->university_no }}</p>
                </div>
                <div>
                    <p class="font-semibold">Cluster/Registration:</p>
                    <p>{{ $record->student->cluster ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="border border-gray-200 rounded mb-6">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left p-3">Description</th>
                        <th class="text-right p-3">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-200">
                        <td class="p-3">{{ $record->sys_type ?? 'General Payment' }}</td>
                        <td class="text-right p-3">{{ number_format($record->amount, 2) }} {{ $record->currency }}</td>
                    </tr>
                    <tr class="bg-gray-50 font-bold">
                        <td class="p-3">TOTAL</td>
                        <td class="text-right p-3">{{ number_format($record->amount, 2) }} {{ $record->currency }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bank Details -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="font-semibold">Bank:</p>
                <p>{{ $record->bank }}</p>
                @if($record->branch)
                    <p class="font-semibold mt-2">Branch:</p>
                    <p>{{ $record->branch }}</p>
                @endif
            </div>
            <div>
                <p class="font-semibold">Status:</p>
                <p class="text-green-600 font-bold">PAYMENT CONFIRMED</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 pt-4 text-center text-sm text-gray-500">
            <p>This is an official receipt. Please retain for your records.</p>
            <p class="mt-1">For inquiries, contact: finance@university.edu</p>
        </div>
    </div>

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
</x-filament::page>
