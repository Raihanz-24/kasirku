<?php

namespace App\Filament\Pages;

use App\Models\BalanceMovement;
use App\Models\SaleItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RiwayatSaldo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'Riwayat Saldo';

    protected static string | \UnitEnum | null $navigationGroup = 'Riwayat';

    protected static ?int $navigationSort = 70;

    protected static ?string $title = 'Riwayat Saldo';

    protected string $view = 'filament.pages.riwayat-saldo';

    public ?string $type = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->form->fill([
            'type' => null,
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
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'sale' => 'Penjualan',
                        'withdrawal' => 'Penarikan',
                        'correction_in' => 'Koreksi masuk',
                        'correction_out' => 'Koreksi keluar',
                    ])
                    ->placeholder('Semua tipe')
                    ->live(),
                DatePicker::make('dateFrom')
                    ->label('Dari tanggal')
                    ->live(),
                DatePicker::make('dateTo')
                    ->label('Sampai tanggal')
                    ->live(),
            ])
            ->columns(3);
    }

    /**
     * @return Collection<int, BalanceMovement>
     */
    public function getMovements(): Collection
    {
        return $this->filteredMovementsQuery()
            ->with('user')
            ->latest('occurred_at')
            ->limit(100)
            ->get();
    }

    public function getTotalIn(): int
    {
        return (int) $this->filteredMovementsQuery()
            ->whereIn('type', ['sale', 'correction_in'])
            ->sum('amount');
    }

    public function getTotalOut(): int
    {
        return (int) $this->filteredMovementsQuery()
            ->whereIn('type', ['withdrawal', 'correction_out'])
            ->sum('amount');
    }

    public function getGrossProfit(): int
    {
        return $this->salesSummaryValue('gross_profit');
    }

    private function salesSummaryValue(string $column): int
    {
        if (filled($this->type) && $this->type !== 'sale') {
            return 0;
        }

        return (int) SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->when(filled($this->dateFrom), fn (Builder $query) => $query->whereDate('sales.occurred_at', '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn (Builder $query) => $query->whereDate('sales.occurred_at', '<=', $this->dateTo))
            ->sum("sale_items.{$column}");
    }

    private function filteredMovementsQuery(): Builder
    {
        return BalanceMovement::query()
            ->when(filled($this->type), fn (Builder $query) => $query->where('type', $this->type))
            ->when(filled($this->dateFrom), fn (Builder $query) => $query->whereDate('occurred_at', '>=', $this->dateFrom))
            ->when(filled($this->dateTo), fn (Builder $query) => $query->whereDate('occurred_at', '<=', $this->dateTo));
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
