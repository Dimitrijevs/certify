<?php

namespace App\Filament\Resources\LearningCertificateResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LearningCertificateResource;

class ListLearningCertificates extends ListRecords
{
    protected static string $resource = LearningCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('tabler-square-plus-2'),
        ];
    }
}