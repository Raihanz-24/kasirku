<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Kategori')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama kategori')
                            ->placeholder('Contoh: Mie Instan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->helperText('Boleh dikosongkan. Sistem akan membuat slug dari nama kategori.')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Kategori aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
