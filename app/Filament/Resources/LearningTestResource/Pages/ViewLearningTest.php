<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\LearningTestResource;

class ViewLearningTest extends ViewRecord
{
    protected static string $resource = LearningTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('tabler-eye-edit'),
        ];
    }
}
