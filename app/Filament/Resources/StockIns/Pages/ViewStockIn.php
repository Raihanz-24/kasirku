<?php

namespace App\Filament\Resources\StockIns\Pages;

use App\Filament\Resources\StockIns\StockInResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStockIn extends ViewRecord
{
    protected static string $resource = StockInResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
