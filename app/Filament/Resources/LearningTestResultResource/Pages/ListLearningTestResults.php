<?php

namespace App\Filament\Resources\LearningTestResultResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LearningTestResource;
use App\Filament\Resources\LearningTestResultResource;

class ListLearningTestResults extends ListRecords
{
    protected static string $resource = LearningTestResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),

            Action::make('view')
                ->label(__('learning/learningTest.label_plural'))
                ->color('gray')
                ->icon('tabler-clipboard-list')
                ->url(LearningTestResource::getUrl('index')),
        ];
    }
}
