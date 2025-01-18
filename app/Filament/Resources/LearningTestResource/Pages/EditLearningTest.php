<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningTestResource;

class EditLearningTest extends EditRecord
{
    protected static string $resource = LearningTestResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('learning/learningTest.custom.edit_qualification');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label(__('learning/learningTest.form.view'))
                ->color('gray')
                ->icon('tabler-eye')
                ->url(LearningTestResource::getUrl('viewTest', ['record' => $this->record->id])),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
        ];
    }
}
