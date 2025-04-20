<?php

namespace App\Filament\Resources\CustomerQuestionResource\Pages;

use App\Filament\Resources\CustomerQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerQuestion extends EditRecord
{
    protected static string $resource = CustomerQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
