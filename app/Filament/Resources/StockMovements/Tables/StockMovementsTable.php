<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'stock_in' => 'Barang masuk',
                        'sale' => 'Penjualan',
                        'adjustment' => 'Koreksi',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'stock_in' => 'success',
                        'sale' => 'warning',
                        'adjustment' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('quantity_in')
                    ->label('Masuk')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('quantity_out')
                    ->label('Keluar')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('stock_before')
                    ->label('Stok sebelum')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('stock_after')
                    ->label('Stok sesudah')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('reference_type')
                    ->label('Sumber')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'App\\Models\\StockIn' => 'Barang masuk',
                        null => '-',
                        default => class_basename($state),
                    })
                    ->searchable(),
                TextColumn::make('reference_id')
                    ->label('ID Ref.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('occurred_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'stock_in' => 'Barang masuk',
                        'sale' => 'Penjualan',
                        'adjustment' => 'Koreksi',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->persistSearchInSession()
            ->persistFiltersInSession();
    }
}
