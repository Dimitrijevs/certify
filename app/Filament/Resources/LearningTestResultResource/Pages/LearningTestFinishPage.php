<?php

namespace App\Filament\Resources\LearningTestResultResource\Pages;

use App\Models\LearningTestResult;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\LearningTestResultResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class LearningTestFinishPage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningTestResultResource::class;

    public function mount($record = null)
    {
        if ($record) {
            $this->record = LearningTestResult::findOrFail($record);
        } else {
            return redirect()->route('filament.app.resources.learning-test-results.index');
        }
    }

    public function getBreadcrumbs(): array
    {
        $resource = __('learning/learningTestResult.label_plural');
        $resource_link = '/learning-test-results';

        return [
            $resource_link => $resource,
            null => 'Finish page',
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return '';
    }

    protected static string $view = 'filament.resources.learning-test-result-resource.pages.learning-test-finish-page';
}