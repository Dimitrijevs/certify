<?php

namespace App\Filament\Resources\LearningCertificateResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningCertificateResource;

class EditLearningCertificate extends EditRecord
{
    protected static string $resource = LearningCertificateResource::class; 

    public function getTitle(): string | Htmlable
    {
        return __("learning/learningCertificate.custom.edit_certificate");
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label(__('learning/learningCertificate.form.view'))
                ->color('gray')
                ->icon('tabler-eye')
                ->url(fn(Model $record) => LearningCertificateResource::getUrl('viewCertificate', ['record' => $record->id])),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
        ];
    }
}
