<?php

namespace App\Filament\Pages;

use App\Models\BalanceMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockIn;
use App\Models\Withdrawal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Laporan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan';

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 80;

    protected static ?string $title = 'Laporan';

    protected string $view = 'filament.pages.laporan';

    public ?string $reportType = 'sales';

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->form->fill([
            'reportType' => 'sales',
            'dateFrom' => now()->startOfMonth()->toDateString(),
            'dateTo' => now()->toDateString(),
        ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isOwner() ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reportType')
                    ->label('Jenis laporan')
                    ->options([
                        'sales' => 'Penjualan',
                        'stock_in' => 'Barang Masuk',
                        'stock' => 'Stok Produk',
                        'balance' => 'Saldo',
                        'withdrawal' => 'Penarikan Saldo',
                    ])
                    ->required()
                    ->live(),
                DatePicker::make('dateFrom')->label('Dari tanggal')->live(),
                DatePicker::make('dateTo')->label('Sampai tanggal')->live(),
            ])
            ->columns(3);
    }

    /** @return Collection<int, Sale> */
    public function getSales(): Collection
    {
        return $this->dateFilter(Sale::query(), 'occurred_at')
            ->with(['user', 'items'])
            ->latest('occurred_at')
            ->limit(200)
            ->get();
    }

    /** @return Collection<int, StockIn> */
    public function getStockIns(): Collection
    {
        return $this->dateFilter(StockIn::query(), 'occurred_at')
            ->with(['product', 'user'])
            ->latest('occurred_at')
            ->limit(200)
            ->get();
    }

    /** @return Collection<int, Product> */
    public function getProducts(): Collection
    {
        return Product::query()->with('category')->orderBy('name')->get();
    }

    /** @return Collection<int, BalanceMovement> */
    public function getBalanceMovements(): Collection
    {
        return $this->dateFilter(BalanceMovement::query(), 'occurred_at')
            ->with('user')
            ->latest('occurred_at')
            ->limit(200)
            ->get();
    }

    /** @return Collection<int, Withdrawal> */
    public function getWithdrawals(): Collection
    {
        return $this->dateFilter(Withdrawal::query(), 'occurred_at')
            ->with('user')
            ->latest('occurred_at')
            ->limit(200)
            ->get();
    }

    public function getSalesTotal(): int
    {
        return (int) $this->dateFilter(Sale::query(), 'occurred_at')->sum('total_amount');
    }

    public function getStockInTotal(): int
    {
        return (int) $this->dateFilter(StockIn::query(), 'occurred_at')->sum('quantity');
    }

    public function getWithdrawalTotal(): int
    {
        return (int) $this->dateFilter(Withdrawal::query(), 'occurred_at')->sum('amount');
    }

    public function getBalanceTotalIn(): int
    {
        return (int) $this->dateFilter(BalanceMovement::query(), 'occurred_at')
            ->whereIn('type', ['sale', 'correction_in'])
            ->sum('amount');
    }

    public function getBalanceTotalOut(): int
    {
        return (int) $this->dateFilter(BalanceMovement::query(), 'occurred_at')
            ->whereIn('type', ['withdrawal', 'correction_out'])
            ->sum('amount');
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    public function movementLabel(string $type): string
    {
        return match ($type) {
            'sale' => 'Penjualan',
            'withdrawal' => 'Penarikan',
            'correction_in' => 'Koreksi masuk',
            'correction_out' => 'Koreksi keluar',
            default => $type,
        };
    }

    private function dateFilter(Builder $query, string $column): Builder
    {
        return $query
            ->when(filled($this->dateFrom), fn (Builder $query) => $query->whereDate($column, '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn (Builder $query) => $query->whereDate($column, '<=', $this->dateTo));
    }
}
