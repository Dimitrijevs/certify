<?php

namespace App\Filament\Resources\LearningCertificateResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Models\LearningCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\LearningCertificateResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class viewCustomLearningCertificate extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningCertificateResource::class;

    public function getTitle(): string
    {
        return __('learning/learningCertificate.label');
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (!$this->record->thumbnail) {
            $actions[] = Action::make('view_pdf')
                ->label(__('learning/learningCertificate.custom.download') . ' PDF')
                ->color('primary')
                ->icon('tabler-file-type-pdf')
                ->url(fn(Model $record) => route('learning-resources.pdf', ['learningResource' => $record->id, 'isDownload' => 1]));
        }

        if (Auth::user()->role_id != 3) {
            $actions[] = Action::make('edit')
                ->label(__('learning/learningCertificate.form.edit'))
                ->color('gray')
                ->icon('tabler-eye-edit')
                ->url(fn(Model $record) => LearningCertificateResource::getUrl('edit', ['record' => $record->id]));
        }

        return $actions;
    }


    public function mount(int | string $record): void
    {
        $this->record = LearningCertificate::findOrFail($record);
    }

    public function isValid()
    {
        $today = date('Y-m-d');

        if (is_null($this->record->valid_to)) {
            return true;
        }

        return $this->record->valid_to <= $today ? $this->record->is_valid = 0 : $this->record->is_valid = 1;
    }

    public function getThumbnailType(): string
    {
        $thumbnailPath = public_path($this->record->thumbnail);

        if (@getimagesize($thumbnailPath) !== false) {
            return 'image';
        }

        $extension = strtolower(pathinfo($thumbnailPath, PATHINFO_EXTENSION));
        if ($extension === 'pdf') {
            return 'pdf';
        }

        return 'other';
    }

    protected static string $view = 'filament.resources.learning-certificate-resource.pages.view-custom-learning-certificate';
}
