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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_bank_account')
                ->label(function () {
                    if (Auth::user()->completed_stripe_onboarding) {
                        return 'Bank Account Added';
                    } else {
                        return 'Add Bank Account';
                    }
                })
                ->color(function () {
                    if (Auth::user()->completed_stripe_onboarding) {
                        return 'success';
                    } else {
                        return 'warning';
                    }
                })
                ->url(function () {
                    return '/stripe/' . Auth::id();
                }),
            Actions\DeleteAction::make()
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
