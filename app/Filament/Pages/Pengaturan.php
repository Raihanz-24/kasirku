<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Pengaturan extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 90;

    protected static ?string $title = 'Pengaturan';

    protected string $view = 'filament.pages.pengaturan';

    public static function canAccess(): bool
    {
        return auth()->user()?->isOwner() ?? false;
    }
}
