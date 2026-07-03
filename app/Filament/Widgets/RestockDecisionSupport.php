<?php

namespace App\Filament\Widgets;

use App\Actions\DecisionSupport\RankRestockProductsAction;
use App\Filament\Resources\Products\ProductResource;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RestockDecisionSupport extends Widget
{
    protected string $view = 'filament.widgets.restock-decision-support';

    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    /** @return Collection<int, array<string, mixed>> */
    public function getRankings(): Collection
    {
        return app(RankRestockProductsAction::class)->execute();
    }

    public function getPeriodLabel(): string
    {
        return today()->subDays(29)->locale('id')->translatedFormat('d M Y')
            .' - '.today()->locale('id')->translatedFormat('d M Y');
    }

    public function getProductUrl(int $productId): string
    {
        return ProductResource::getUrl('view', ['record' => $productId]);
    }
}
