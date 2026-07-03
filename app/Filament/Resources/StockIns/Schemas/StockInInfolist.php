<?php

namespace App\Filament\Resources\StockIns\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockInInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Barang Masuk')
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Produk'),
                        TextEntry::make('product.sku')
                            ->label('SKU'),
                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),
                        TextEntry::make('quantity')
                            ->label('Jumlah')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('cost_price')
                            ->label('Harga modal')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->placeholder('-'),
                        TextEntry::make('selling_price')
                            ->label('Harga jual')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->placeholder('-'),
                        TextEntry::make('stock_before')
                            ->label('Stok sebelum')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('stock_after')
                            ->label('Stok sesudah')
                            ->numeric(decimalPlaces: 0, locale: 'id'),
                        TextEntry::make('occurred_at')
                            ->label('Tanggal masuk')
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
