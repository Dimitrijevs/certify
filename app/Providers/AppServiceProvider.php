<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;
use App\Http\Responses\CustomLogoutResponse;
use Filament\Forms\Components\Actions\Action;
use Filament\Http\Responses\Auth\LogoutResponse;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the custom LogoutResponse implementation
        $this->app->singleton(LogoutResponse::class, CustomLogoutResponse::class);
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
