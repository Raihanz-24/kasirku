<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('type')
                    ->required(),
                TextInput::make('quantity_in')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->default(0),
                TextInput::make('quantity_out')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->default(0),
                TextInput::make('stock_before')
                    ->required()
                    ->numeric()
                    ->integer(),
                TextInput::make('stock_after')
                    ->required()
                    ->numeric()
                    ->integer(),
                TextInput::make('reference_type'),
                TextInput::make('reference_id')
                    ->numeric(),
                DateTimePicker::make('occurred_at')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
