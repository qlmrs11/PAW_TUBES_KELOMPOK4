<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WPengeluaranChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran';
    protected static string $color = 'danger';

    protected function getData(): array
    {
        $data = Trend::query(Transaction::expenses())
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
