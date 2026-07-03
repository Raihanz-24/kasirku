<?php

namespace App\Filament\Pages;

use App\Actions\Finance\GetStoreBalanceAction;
use App\Models\BalanceMovement;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class SaldoToko extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Saldo Toko';

    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 40;

    protected static ?string $title = 'Saldo Toko';

    protected string $view = 'filament.pages.saldo-toko';

    public static function canAccess(): bool
    {
        return auth()->user()?->isOwner() ?? false;
    }

    public function getCurrentBalance(): int
    {
        return (int) app(GetStoreBalanceAction::class)->execute()->current_balance;
    }

    public function getTodayIncome(): int
    {
        return (int) BalanceMovement::query()
            ->where('type', 'sale')
            ->whereDate('occurred_at', today())
            ->sum('amount');
    }

    public function getThisMonthIncome(): int
    {
        return (int) BalanceMovement::query()
            ->where('type', 'sale')
            ->whereBetween('occurred_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
    }

    public function getThisMonthOutcome(): int
    {
        return (int) BalanceMovement::query()
            ->whereIn('type', ['withdrawal', 'correction_out'])
            ->whereBetween('occurred_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');
    }

    /**
     * @return Collection<int, BalanceMovement>
     */
    public function getRecentMovements(): Collection
    {
        return BalanceMovement::query()
            ->with('user')
            ->latest('occurred_at')
            ->limit(10)
            ->get();
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    public function movementTypeLabel(?string $type): string
    {
        return match ($type) {
            'sale' => 'Penjualan',
            'withdrawal' => 'Penarikan',
            'correction_in' => 'Koreksi masuk',
            'correction_out' => 'Koreksi keluar',
            default => '-',
        };
    }

    public function movementTone(?string $type): string
    {
        return match ($type) {
            'sale', 'correction_in' => 'in',
            'withdrawal', 'correction_out' => 'out',
            default => 'neutral',
        };
    }
}
