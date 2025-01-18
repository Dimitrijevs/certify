<?php

namespace App\Filament\Resources\LearningCertificateResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\LearningCertificateResource;

class CreateLearningCertificate extends CreateRecord
{
    protected static string $resource = LearningCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label(__('learning/learningResource.form.create'))
                ->action('create')
                ->icon('tabler-playlist-add'),
        ];
    }
}
