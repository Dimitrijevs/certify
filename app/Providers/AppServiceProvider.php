<?php

namespace App\Providers;

use Stripe\StripeClient;
use Filament\Tables\Table;
use Filament\Forms\Components\Field;
use Illuminate\Support\ServiceProvider;
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
        $this->app->singleton(StripeClient::class, function () {
            return new StripeClient(config('stripe.secret'));
        });

        Table::configureUsing(function (Table $table): void {
            $table->paginationPageOptions([10, 20, 50, 100])
                ->defaultPaginationPageOption(20);
        });

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
