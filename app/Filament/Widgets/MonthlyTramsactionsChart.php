<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

class MonthlyTramsactionsChart extends ChartWidget
{

    // ubah di uuttan ke dua bawah card 
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Transaksi bulan ini';

    protected function getData(): array
    {
        $data = Trend::model(Transaction::class)
            ->between(start: now()->startOfMonth(), end: now()->endOfMonth())->perDay()->count();
        return [
            'datasets' => [
                [
                    'label' => 'Data Transaksi',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): string|Htmlable|null
    {
        return 'Transaksi yang dilakukan bulan ini';
    }
}
