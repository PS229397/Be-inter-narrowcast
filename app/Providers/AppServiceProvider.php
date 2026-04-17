<?php

namespace App\Providers;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('layout-builder', resource_path('css/filament/layout-builder.css'))
                ->loadedOnRequest(),
            AlpineComponent::make('layout-builder', resource_path('js/filament/components/layout-builder.js')),
            AlpineComponent::make('custom-component-preview', resource_path('js/filament/components/custom-component-preview.js')),
        ]);
    }
}
