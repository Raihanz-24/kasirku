<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Kategori')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama kategori'),
                        TextEntry::make('slug')
                            ->label('Slug'),
                        TextEntry::make('products_count')
                            ->label('Jumlah produk')
                            ->state(fn (Category $record): int => $record->products()->count()),
                        IconEntry::make('is_active')
                            ->label('Kategori aktif')
                            ->boolean(),
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
                            ->visible(fn (Category $record): bool => $record->trashed()),
                    ])
                    ->columns(3),
            ]);
    }
}
