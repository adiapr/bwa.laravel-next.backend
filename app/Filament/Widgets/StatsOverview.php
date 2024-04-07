<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{

    private function getPercentage(int $from, int $to)
    {
        return $to - $from / ($to + $from / 2) * 100;
    }

    protected function getStats(): array
    {
        $newListing = Listing::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $transactions = Transaction::whereStatus('approved')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
        $prevTransactions = Transaction::whereStatus('approved')->whereMonth('created_at', Carbon::now()->subMonth())->whereYear('created_at', Carbon::now()->subMonth()->year)->get();
        $transactionsPercentage = $this->getPercentage($prevTransactions->count(), $transactions->count());
        $revenuePercentage = $this->getPercentage($prevTransactions->sum('total_price'), $transactions->sum('total_price'));
        
        return [
            Stat::make('Listing baru bulan ini', $newListing),

            Stat::make('Transaksi baru bulan ini', $transactions->count())
                ->description($transactionsPercentage > 0 ? "{$transactionsPercentage}% naik" : "{$transactionsPercentage}% turun")
                ->descriptionIcon($transactionsPercentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transactionsPercentage > 0 ? 'success' : 'danger'),

            Stat::make('Penghasilan baru bulan ini', Number::currency($transactions->sum('total_price'), 'USD'))
                ->description($revenuePercentage > 0 ? "{$revenuePercentage}% naik" : "{$revenuePercentage}% turun")
                ->descriptionIcon($revenuePercentage > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transactionsPercentage > 0 ? 'success' : 'danger'),
        ];
    }
}
