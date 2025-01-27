<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\CertificateRequirementsLogic;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use CertificateRequirementsLogic;
    
    protected static string $resource = UserResource::class;

    public string $place = 'edit'; 

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
