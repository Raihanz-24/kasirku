<?php

namespace App\Filament\Resources\StockIns;

use App\Filament\Resources\StockIns\Pages\CreateStockIn;
use App\Filament\Resources\StockIns\Pages\ListStockIns;
use App\Filament\Resources\StockIns\Pages\ViewStockIn;
use App\Filament\Resources\StockIns\Schemas\StockInForm;
use App\Filament\Resources\StockIns\Schemas\StockInInfolist;
use App\Filament\Resources\StockIns\Tables\StockInsTable;
use App\Models\StockIn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockInResource extends Resource
{
    protected static ?string $model = StockIn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?string $modelLabel = 'Barang Masuk';

    protected static ?string $pluralModelLabel = 'Barang Masuk';

    protected static ?string $recordTitleAttribute = 'product.name';

    protected static ?string $slug = 'barang-masuk';

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return StockInForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StockInInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockInsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockIns::route('/'),
            'create' => CreateStockIn::route('/create'),
            'view' => ViewStockIn::route('/{record}'),
        ];
    }
}
