<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningTestResource;

class CreateLearningTest extends CreateRecord
{
    protected static string $resource = LearningTestResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('learning/learningTest.custom.new_test');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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
