<?php

namespace App\Filament\Resources\StockIns\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StockInForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Input Barang Masuk')
                    ->description('Pilih produk, isi jumlah masuk, dan perbarui harga bila diperlukan.')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship(
                                name: 'product',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('name'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Product $record): string => "{$record->name} ({$record->sku}) - stok {$record->current_stock} {$record->unit}")
                            ->searchable(['name', 'sku', 'barcode'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?int $state): void {
                                $product = $state ? Product::query()->find($state) : null;

                                $set('cost_price', $product?->cost_price ?? 0);
                                $set('selling_price', $product?->selling_price ?? 0);
                            })
                            ->required()
                            ->autofocus(),
                        TextInput::make('quantity')
                            ->label('Jumlah masuk')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->step(1)
                            ->placeholder('Contoh: 40'),
                        TextInput::make('cost_price')
                            ->label('Harga modal')
                            ->helperText('Jika diisi, harga modal produk ikut diperbarui.')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp'),
                        TextInput::make('selling_price')
                            ->label('Harga jual')
                            ->helperText('Jika diisi, harga jual produk ikut diperbarui untuk transaksi berikutnya.')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Rp'),
                        DateTimePicker::make('occurred_at')
                            ->label('Tanggal masuk')
                            ->required()
                            ->default(now()),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->placeholder('Opsional')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
