<?php

namespace App\Filament\Resources\StockIns\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockInsTable
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
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('cost_price')
                    ->label('Harga modal')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('selling_price')
                    ->label('Harga jual')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('stock_before')
                    ->label('Stok sebelum')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('stock_after')
                    ->label('Stok sesudah')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('occurred_at')
                    ->label('Tanggal masuk')
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
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->persistSearchInSession()
            ->persistFiltersInSession();
    }
}
