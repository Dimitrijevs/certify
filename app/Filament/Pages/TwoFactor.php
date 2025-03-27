<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Jeffgreco13\FilamentBreezy\Actions\PasswordButtonAction;

class TwoFactor extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Settings::class;

    protected static string $view = 'filament.pages.two-factor';

    public function getBreadcrumbs(): array
    {
        return [
            '/user-cabinet/general' => __('userCabinet.user_cabinet_breadcrumb'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('userCabinet.labels.security');
    }

    public function getTitle(): string|Htmlable
    {
        return __('userCabinet.labels.security');
    }

    public $user;

    public $code;

    public bool $showRecoveryCodes = false;

    public static $sort = 30;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
    }

    public function enableAction(): Action
    {
        return PasswordButtonAction::make('enable')
            ->label(__('userCabinet.2fa_enable_button'))
            ->action(function () {
                $this->user->enableTwoFactorAuthentication();
                Notification::make()
                    ->success()
                    ->title(__('userCabinet.notifications.2fa_enabling_stage'))
                    ->send();
            });
    }

    public function disableAction(): Action
    {
        return PasswordButtonAction::make('disable')
            ->label(__('userCabinet.disable'))
            ->color('primary')
            ->requiresConfirmation()
            ->action(function () {
                $this->user->disableTwoFactorAuthentication();
                Notification::make()
                    ->warning()
                    ->title(__('userCabinet.notifications.2fa_disabled'))
                    ->send();
            });
    }

    public function confirmAction(): Action
    {
        return Action::make('confirm')
            ->color('success')
            ->label(__('userCabinet.confirm_and_finish'))
            ->modalWidth('sm')
            ->form([
                TextInput::make('code')
                    ->label(__('userCabinet.enter_code'))
                    ->placeholder('###-###')
                    ->required(),
            ])
            ->action(function ($data, $action, $livewire) {
                if (! filament('filament-breezy')->verify(code: $data['code'])) {
                    $livewire->addError('mountedActionsData.0.code', __('filament-breezy::default.profile.2fa.confirmation.invalid_code'));
                    $action->halt();
                }
                $this->user->confirmTwoFactorAuthentication();
                $this->user->setTwoFactorSession();
                Notification::make()
                    ->success()
                    ->title(__('userCabinet.2fa_enabled'))
                    ->send();
            });
    }

    public function regenerateCodesAction(): Action
    {
        return PasswordButtonAction::make('regenerateCodes')
            ->label(__('userCabinet.regenerate_recovery_codes'))
            ->requiresConfirmation()
            ->action(function () {
                // These needs to regenerate the codes, then show the section.
                $this->user->reGenerateRecoveryCodes();
                $this->showRecoveryCodes = true;
                Notification::make()
                    ->success()
                    ->title(__('userCabinet.notifications.generate_recovery_codes'))
                    ->send();
            });
    }

    public function getRecoveryCodesProperty(): Collection
    {
        return collect($this->user->two_factor_recovery_codes ?? []);
    }

    public function getTwoFactorQrCode()
    {
        return filament('filament-breezy')->getTwoFactorQrCodeSvg($this->user->getTwoFactorQrCodeUrl());
    }

    public function toggleRecoveryCodes()
    {
        $this->showRecoveryCodes = ! $this->showRecoveryCodes;
    }

    public function showRequiresTwoFactorAlert()
    {
        return filament('filament-breezy')->shouldForceTwoFactor();
    }
}
