<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;
use Filament\Forms\Components\Actions\Action;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'lv', 'ru']);
        });

        Field::macro("tooltip", function (string $tooltip) {
            return $this->hintAction(
                function () use ($tooltip) {
                    return Action::make('help')
                        ->icon('tabler-help')
                        ->extraAttributes(["class" => "text-gray-500"])
                        ->label("")
                        ->tooltip($tooltip);
                }
            );
        });
    }
}
