<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
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
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('other.edit_record');
    }
}
