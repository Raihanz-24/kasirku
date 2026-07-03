<?php

namespace App\Filament\Widgets;

use App\Actions\Finance\GetStoreBalanceAction;
use App\Models\User;
use Filament\Widgets\Widget;

class DashboardWelcome extends Widget
{
    protected string $view = 'filament.widgets.dashboard-welcome';

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public function getCurrentBalance(): int
    {
        return (int) app(GetStoreBalanceAction::class)->execute()->current_balance;
    }

    public function getActiveUsers(): int
    {
        return User::query()->count();
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
