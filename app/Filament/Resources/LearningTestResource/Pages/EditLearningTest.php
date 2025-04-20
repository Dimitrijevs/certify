<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningTestResource;

class EditLearningTest extends EditRecord
{
    protected static string $resource = LearningTestResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();

        if ($this->record->created_by != Auth::id() && Auth::user()->role_id > 3) {
            Notification::make()
                ->title('You are not authorized to edit this test')
                ->danger()
                ->send();

            $this->redirect($this->previousUrl);
        }
    }

    public function getTitle(): string|Htmlable
    {
        return __('learning/learningTest.custom.edit_test');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label(__('learning/learningTest.form.view'))
                ->color('gray')
                ->icon('tabler-eye')
                ->url(LearningTestResource::getUrl('viewTest', ['record' => $this->record->id])),
            Action::make('verify')
                ->label('Verify')
                ->visible(function () {
                    if (Auth::user()->role_id < 3 && $this->record->is_public == false) {
                        return true;
                    }

                    return false;
                })
                ->color('primary')
                ->icon('tabler-circle-check')
                ->action(function () {
                    $this->record->is_public = true;
                    $this->record->aproved_by = Auth::id();
                    $this->record->saveQuietly();

                    return Notification::make()
                        ->title('Category verified')
                        ->success()
                        ->send();
                }),
            Action::make('unverify')
                ->label('Unverify')
                ->visible(function () {
                    if (Auth::user()->role_id < 3 && $this->record->is_public == true) {
                        return true;
                    }

                    return false;
                })
                ->color('danger')
                ->icon('tabler-circle-x')
                ->action(function () {
                    $this->record->is_public = false;
                    $this->record->aproved_by = Auth::id();
                    $this->record->saveQuietly();

                    return Notification::make()
                        ->title('Category unverified')
                        ->success()
                        ->send();
                }),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
        ];
    }
}
