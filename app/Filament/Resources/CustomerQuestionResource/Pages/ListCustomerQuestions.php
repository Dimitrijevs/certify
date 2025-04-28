<?php

namespace App\Filament\Resources\CustomerQuestionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CustomerQuestionResource;

class ListCustomerQuestions extends ListRecords
{
    protected static string $resource = CustomerQuestionResource::class;

    public function mount(): void
    {
        if (Auth::user()->role_id > 2) {
            Notification::make()
                ->title('You do not have permissions to access this page')
                ->danger()
                ->send();

            $this->redirect(route('filament.app.pages.dashboard'));
        }
        $this->authorizeAccess();

        $this->loadDefaultActiveTab();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
