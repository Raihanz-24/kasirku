<?php

namespace App\Filament\Pages;

use App\Actions\Finance\CreateWithdrawalAction;
use App\Actions\Finance\GetStoreBalanceAction;
use App\Models\Withdrawal;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PenarikanSaldo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Penarikan Saldo';

    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 50;

    protected static ?string $title = 'Penarikan Saldo';

    protected string $view = 'filament.pages.penarikan-saldo';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'amount' => null,
            'purpose' => null,
            'notes' => null,
            'occurred_at' => now(),
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
                TextInput::make('amount')
                    ->label('Nominal penarikan')
                    ->prefix('Rp')
                    ->numeric()
                    ->minValue(1)
                    ->step(1)
                    ->required()
                    ->live(debounce: 300)
                    ->helperText('Maksimal sesuai saldo toko yang tersedia.'),
                TextInput::make('purpose')
                    ->label('Keperluan')
                    ->placeholder('Contoh: Belanja stok atau bayar listrik')
                    ->maxLength(255)
                    ->required(),
                DateTimePicker::make('occurred_at')
                    ->label('Tanggal penarikan')
                    ->seconds(false)
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->placeholder('Catatan tambahan (opsional)')
                    ->rows(3)
                    ->maxLength(2000)
                    ->columnSpanFull(),
            ])
            ->statePath('data')
            ->columns(2);
    }

    public function createWithdrawal(): void
    {
        $data = $this->form->getState();

        try {
            $withdrawal = app(CreateWithdrawalAction::class)->execute($data, auth()->user());
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Penarikan belum bisa disimpan')
                ->body(collect($exception->errors())->flatten()->first())
                ->danger()
                ->send();

            return;
        }

        $this->form->fill([
            'amount' => null,
            'purpose' => null,
            'notes' => null,
            'occurred_at' => now(),
        ]);

        Notification::make()
            ->title('Penarikan berhasil disimpan')
            ->body("Saldo toko sekarang {$this->rupiah($withdrawal->balance_after)}.")
            ->success()
            ->send();
    }

    public function getCurrentBalance(): int
    {
        return (int) app(GetStoreBalanceAction::class)->execute()->current_balance;
    }

    public function getBalanceAfterPreview(): int
    {
        $amount = max(0, (int) ($this->data['amount'] ?? 0));

        return max(0, $this->getCurrentBalance() - $amount);
    }

    /** @return Collection<int, Withdrawal> */
    public function getRecentWithdrawals(): Collection
    {
        return Withdrawal::query()
            ->with('user')
            ->latest('occurred_at')
            ->limit(8)
            ->get();
    }

    public function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
