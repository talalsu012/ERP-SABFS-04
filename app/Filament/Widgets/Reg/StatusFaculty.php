<?php

// namespace App\Filament\Widgets\Reg;

// use Filament\Widgets\ChartWidget;

// class StatusFaculty extends ChartWidget
// {
//     protected static ?string $heading = 'Chart';

//     protected function getData(): array
//     {
//         return [
//             //
//         ];
//     }

//     protected function getType(): string
//     {
//         return 'line';
//     }
// }


namespace App\Filament\Widgets\Reg;

use App\Models\Reg\Student;
use App\Models\Reg\Faculty;
use App\Models\Reg\Lt_column;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatusFaculty extends ChartWidget
{

    protected static ?int $sort = 2;
    protected static string $color = 'info';



    public function getDescription(): ?string
    {
        return __('reg.statusReportwidegt');
    }

    protected function getData(): array
    {
        // Get all faculties
        $faculties = Faculty::orderBy('name')->get();

        // Get all registration statuses
        $statuses = Lt_column::where('grouped', '2007')->orderBy('name')->get();

        // Prepare datasets
        $datasets = [];

        foreach ($statuses as $status) {
            $data = [];

            foreach ($faculties as $faculty) {
                $count = Student::where('faculty_code', $faculty->code)
                    ->where('status_code', $status->code)
                    ->count();

                $data[] = $count;
            }

            $datasets[] = [
                'label' => $status->name,
                'data' => $data,
                'borderColor' => $this->getStatusColor($status->code),
                'backgroundColor' => $this->getStatusColor($status->code, 0.2),
                'tension' => 0.1,
            ];
        }

        return [
            'labels' => $faculties->pluck('name')->toArray(),
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => __('reg.students'),
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => __('reg.faculties'),
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    private function getStatusColor(string $statusCode, float $opacity = 1): string
    {
        $colors = [
            '20070001' => 'rgba(75, 192, 192, '.$opacity.')', // Complete - teal
            '20070002' => 'rgba(255, 206, 86, '.$opacity.')',  // Installment - yellow
            '20070003' => 'rgba(255, 99, 132, '.$opacity.')',  // Unregistered - red
            // '20070004' => 'rgba(54, 162, 235, '.$opacity.')',  // Other status - blue
        ];

        return $colors[$statusCode] ?? 'rgba(153, 102, 255, '.$opacity.')'; // default purple
    }

    // Make widget full height as well
    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getHeight(): string
    {
        return '400px'; // Adjust height as needed
    }
}
