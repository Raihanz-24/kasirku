<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Riwayat Stok')
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Produk'),
                        TextEntry::make('product.sku')
                            ->label('SKU'),
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),
                        TextEntry::make('type')
                            ->label('Tipe')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'stock_in' => 'Barang masuk',
                                'sale' => 'Penjualan',
                                'adjustment' => 'Koreksi',
                                default => $state,
                            })
                            ->badge(),
                        TextEntry::make('quantity_in')
                            ->label('Masuk')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('quantity_out')
                            ->label('Keluar')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('stock_before')
                            ->label('Stok sebelum')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('stock_after')
                            ->label('Stok sesudah')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('reference_type')
                            ->label('Sumber')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'App\\Models\\StockIn' => 'Barang masuk',
                                null => '-',
                                default => class_basename($state),
                            })
                            ->placeholder('-'),
                        TextEntry::make('reference_id')
                            ->label('ID Ref.')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('occurred_at')
                            ->label('Tanggal')
                            ->dateTime(),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Audit')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Diubah')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
