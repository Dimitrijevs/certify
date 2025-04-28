<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class Register extends BaseRegister
{
    use WithRateLimiting {
        WithRateLimiting::rateLimit as baseRateLimit;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getCountryFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCountryFormComponent(): Component
    {
        return Country::make('country')
            ->label('Country')
            ->preload()
            ->searchable()
            ->required();
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Name')
            ->required()
            ->maxLength(255)
            ->unique(User::class, 'name');
    }

    protected function rateLimit($maxAttempts, $decaySeconds = 60, $method = null, $component = null): void
    {
        $this->baseRateLimit(120, $decaySeconds, $method, $component);
    }
}