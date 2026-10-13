<?php

namespace App\Filament\Widgets\Reg;

use App\Models\Reg\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentStatistics extends ChartWidget
{

    protected static ?int $sort = 3;
    protected static string $color = 'success';


        protected function getData(): array
        {
            // Get payment trends - count and sum by day
            $paymentCountData = Trend::model(Payment::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count();

            $paymentSumData = Trend::model(Payment::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->sum('amount');

            return [
                'labels' => $paymentCountData->map(fn (TrendValue $value) => $value->date),
                'datasets' => [
                    [
                        'label' => __('Number of Payments'),
                        'data' => $paymentCountData->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'yAxisID' => 'y',
                        'tension' => 0.1
                    ],
                    [
                        'label' => __('Total Amount'),
                        'data' => $paymentSumData->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'yAxisID' => 'y1',
                        'tension' => 0.1
                    ]
                ],
            ];
        }

        protected function getType(): string
        {
            return 'line';
        }

        protected function getOptions(): array
        {
            return [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'left',
                        'title' => [
                            'display' => true,
                            'text' => __('Number of Payments'),
                        ],
                        'min' => 0,
                    ],
                    'y1' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'right',
                        'title' => [
                            'display' => true,
                            'text' => __('Total Amount'),
                        ],
                        'min' => 0,
                        'grid' => [
                            'drawOnChartArea' => false,
                        ],
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                    'tooltip' => [
                        'mode' => 'index',
                        'intersect' => false,
                        'callbacks' => [
                            'label' => function($context) {
                                return $context->dataset->label . ': ' .
                                    ($context->dataset->yAxisID === 'y1'
                                        ? number_format($context->raw, 2)
                                        : $context->raw);
                            }
                        ]
                    ],
                ],
            ];
        }

        protected function getFilters(): ?array
        {
            return [
                'week' => __('Last Week'),
                'month' => __('Last Month'),
                'year' => __('Last Year'),
            ];
        }

        protected function getFilteredData(?string $filter): array
        {
            $startDate = match($filter) {
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => now()->subMonth(),
            };

            $paymentCountData = Trend::model(Payment::class)
                ->between($startDate, now())
                ->perDay()
                ->count();

            $paymentSumData = Trend::model(Payment::class)
                ->between($startDate, now())
                ->perDay()
                ->sum('amount');

            return [
                'labels' => $paymentCountData->map(fn (TrendValue $value) => $value->date),
                'datasets' => [
                    [
                        'label' => __('Number of Payments'),
                        'data' => $paymentCountData->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'yAxisID' => 'y',
                        'tension' => 0.1
                    ],
                    [
                        'label' => __('Total Amount'),
                        'data' => $paymentSumData->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'yAxisID' => 'y1',
                        'tension' => 0.1
                    ]
                ],
            ];
        }

        public function getColumnSpan(): int|string|array
        {
            return 'full';
        }

        protected function getHeight(): string
        {
            return '400px';
        }
    }
