<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LearningTestResource;

class ListLearningTests extends ListRecords
{
    protected static string $resource = LearningTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('view')
            //     ->label(__('learning/learningTestResult.label_plural'))
            //     ->icon('tabler-checkbox')
            //     ->color('gray')
            //     ->url(ListLearningTestResults::getUrl(['index'])),

            CreateAction::make()
                ->label(__('learning/learningTest.form.create_new_qualification'))
                ->icon('tabler-square-plus-2'),
        ];
    }
}
