<?php

namespace App\Filament\Resources\LearningTestResultResource\Pages;

use Livewire\Attributes\On;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\LearningTestResultResource;

class EditLearningTestResult extends EditRecord
{
    protected static string $resource = LearningTestResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('View')
                ->color('gray')
                ->icon('tabler-eye')
                ->url(LearningTestResultResource::getUrl('do-test', ['record' => $this->record->id, 'question' => 1, 'viewTest' => 1])),
            DeleteAction::make()
                ->after(function () {
                    return redirect(LearningTestResultResource::getUrl());
                })
                ->icon('tabler-trash'),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
        ];
    }

    #[On('refreshTestResult')]
    public function refresh($points): void
    {
        $record = $this->record;
        $points = $record->details->sum('points');

        $points >= $record->test->min_score ? $record->is_passed = true : $record->is_passed = false;

        $record->points = $points;
        $record->save();

        $this->fillForm();
    }
}
