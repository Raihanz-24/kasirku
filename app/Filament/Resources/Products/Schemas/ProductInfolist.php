<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama produk'),
                        TextEntry::make('sku')
                            ->label('SKU'),
                        TextEntry::make('barcode')
                            ->label('Barcode')
                            ->placeholder('-'),
                        TextEntry::make('category.name')
                            ->label('Kategori')
                            ->placeholder('-')
                            ->badge(),
                        TextEntry::make('unit')
                            ->label('Satuan')
                            ->badge(),
                        IconEntry::make('is_active')
                            ->label('Produk aktif')
                            ->boolean(),
                    ])
                    ->columns(2),

                Section::make('Harga dan Stok')
                    ->schema([
                        TextEntry::make('cost_price')
                            ->label('Harga modal')
                            ->money('IDR', locale: 'id', decimalPlaces: 0),
                        TextEntry::make('selling_price')
                            ->label('Harga jual')
                            ->money('IDR', locale: 'id', decimalPlaces: 0),
                        TextEntry::make('current_stock')
                            ->label('Stok saat ini')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('minimum_stock')
                            ->label('Stok minimum')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('stock_status')
                            ->label('Status stok')
                            ->state(fn (Product $record): string => $record->isLowStock() ? 'Menipis' : 'Aman')
                            ->badge()
                            ->color(fn (string $state): string => $state === 'Menipis' ? 'danger' : 'success'),
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
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime()
                            ->visible(fn (Product $record): bool => $record->trashed()),
                    ])
                    ->columns(3),
            ]);
    }
}
