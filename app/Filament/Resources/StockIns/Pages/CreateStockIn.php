<?php

namespace App\Filament\Resources\StockIns\Pages;

use App\Actions\Inventory\RecordStockInAction;
use App\Filament\Resources\StockIns\StockInResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateStockIn extends CreateRecord
{
    protected static string $resource = StockInResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(RecordStockInAction::class)->execute($data, auth()->user());
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Barang masuk berhasil dicatat';
    }
}
