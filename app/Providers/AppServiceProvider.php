<?php

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;
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
                ->locales(['en','lv','ru']);
        });

        Filament::registerNavigationGroups([
            'Learning' => NavigationGroup::make()
                ->label(__('learning/learningCategory.group_label'))
                ->icon('tabler-book'),
        ]);

        Field::macro("tooltip", function (string $tooltip) {
            return $this->hint(
                Action::make('help')
                    ->icon('tabler-exclamation-circle')
                    ->color('gray')
                    ->label('')
                    ->tooltip($tooltip)
            );
        });
    }
}
