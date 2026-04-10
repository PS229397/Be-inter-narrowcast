<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\DisplayTokens;
use App\Filament\App\Resources\CategoryResource;
use App\Filament\App\Resources\LocationResource;
use App\Filament\App\Resources\SlideResource;
use App\Filament\App\Resources\SlideshowResource;
use App\Filament\App\Widgets\CustomerStatsOverview;
use App\Http\Middleware\ApplyCustomerScope;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->login()
            ->authGuard('web')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                LocationResource::class,
                CategoryResource::class,
                SlideResource::class,
                SlideshowResource::class,
            ])
            ->pages([
                Dashboard::class,
                DisplayTokens::class,
            ])
            ->widgets([
                AccountWidget::class,
                CustomerStatsOverview::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Screens'),
                NavigationGroup::make('Content'),
                NavigationGroup::make('Access'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                ValidateCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                ApplyCustomerScope::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_AFTER,
                fn (): string => view('filament.app.impersonation-banner')->render(),
            );
    }
}
