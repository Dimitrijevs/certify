<?php

namespace App\Filament\Resources\CustomerQuestionResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomerQuestionResource;

class EditCustomerQuestion extends EditRecord
{
    protected static string $resource = CustomerQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
        ];
    }
}
