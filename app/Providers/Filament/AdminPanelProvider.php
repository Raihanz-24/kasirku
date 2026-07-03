<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\HtmlString;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->spa(hasPrefetching: true)
            ->brandName('Kasirku')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <link rel="manifest" href="/manifest.webmanifest">
                    <link rel="apple-touch-icon" href="/icons/kasirku-192.png">
                    <meta name="theme-color" content="#102a43">
                    <meta name="mobile-web-app-capable" content="yes">
                    <meta name="apple-mobile-web-app-capable" content="yes">
                    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
                    <script>
                        window.kasirkuInstallPrompt = null;
                        window.addEventListener('beforeinstallprompt', (event) => {
                            event.preventDefault();
                            window.kasirkuInstallPrompt = event;
                            window.dispatchEvent(new CustomEvent('kasirku-install-ready'));
                        });
                        window.addEventListener('appinstalled', () => {
                            window.kasirkuInstallPrompt = null;
                            window.dispatchEvent(new CustomEvent('kasirku-installed'));
                        });
                        if ('serviceWorker' in navigator) {
                            window.addEventListener('load', () => navigator.serviceWorker.register('/service-worker.js'));
                        }
                    </script>
                    HTML),
            )
            ->navigationGroups([
                'Keuangan',
                'Operasional',
                'Master Data',
                'Riwayat',
                'Laporan',
                'Sistem',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
