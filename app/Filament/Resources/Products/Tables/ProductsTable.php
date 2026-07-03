<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('barcode')
                    ->label('Barcode')
                    ->placeholder('-')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                TextColumn::make('unit')
                    ->label('Satuan')
                    ->searchable()
                    ->badge(),
                TextColumn::make('cost_price')
                    ->label('Modal')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('selling_price')
                    ->label('Jual')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('current_stock')
                    ->label('Stok')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('minimum_stock')
                    ->label('Min.')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('stock_status')
                    ->label('Status stok')
                    ->state(fn (Product $record): string => $record->isLowStock() ? 'Menipis' : 'Aman')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Menipis' ? 'danger' : 'success'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_active')
                    ->label('Status aktif')
                    ->placeholder('Semua status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                Filter::make('low_stock')
                    ->label('Stok menipis')
                    ->query(fn (Builder $query): Builder => $query->whereColumn('current_stock', '<=', 'minimum_stock')),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->persistSearchInSession()
            ->persistFiltersInSession();
    }
}
