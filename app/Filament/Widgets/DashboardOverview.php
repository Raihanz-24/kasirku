<?php

namespace App\Filament\Widgets;

use App\Actions\Finance\GetStoreBalanceAction;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockIn;
use App\Models\Withdrawal;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class DashboardOverview extends Widget
{
    protected string $view = 'filament.widgets.dashboard-overview';

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getCurrentBalance(): int
    {
        return (int) app(GetStoreBalanceAction::class)->execute()->current_balance;
    }

    public function getTodaySales(): int
    {
        return (int) Sale::query()->whereDate('occurred_at', today())->sum('total_amount');
    }

    public function getTodayTransactions(): int
    {
        return Sale::query()->whereDate('occurred_at', today())->count();
    }

    public function getTodayStockIn(): int
    {
        return (int) StockIn::query()->whereDate('occurred_at', today())->sum('quantity');
    }

    public function getMonthlyWithdrawals(): int
    {
        return (int) Withdrawal::query()
            ->whereBetween('occurred_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
    }

    /** @return Collection<int, Product> */
    public function getLowStockProducts(): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->limit(8)
            ->get();
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
