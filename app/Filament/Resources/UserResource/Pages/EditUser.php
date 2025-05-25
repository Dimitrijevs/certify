<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\CertificateRequirementsLogic;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditUser extends EditRecord
{
    use CertificateRequirementsLogic;

    protected static string $resource = UserResource::class;

    public string $place = 'edit';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_bank_account')
                ->label(function () {
                    if (Auth::user()->completed_stripe_onboarding) {
                        return __('user.visit_dashboard');
                    } else {
                        return __('user.add_bank_account');
                    }
                })
                ->icon(function () {
                    if (Auth::user()->completed_stripe_onboarding) {
                        return 'tabler-check';
                    } else {
                        return 'tabler-plus';
                    }
                })
                ->color(function () {
                    if (Auth::user()->completed_stripe_onboarding) {
                        return 'primary';
                    } else {
                        return 'warning';
                    }
                })
                ->url(function () {
                    return '/stripe/' . Auth::id();
                }),
            Actions\DeleteAction::make()
                ->hidden(function ($record) {
                    return Auth::id() == $record->id && Auth::user()->role_id == 1;
                })
                ->icon('tabler-trash'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('user.fields.edit_user');
    }

    #[On('update-user-edit-page')]
    public function refresh()
    {
        // ...
    }
}
