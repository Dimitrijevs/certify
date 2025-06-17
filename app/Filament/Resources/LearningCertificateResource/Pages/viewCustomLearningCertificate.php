<?php

namespace App\Filament\Resources\LearningCertificateResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Models\LearningCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
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
        return [
            Action::make('view_pdf')
                ->label(__('learning/learningCertificate.custom.download') . ' PDF')
                ->color('primary')
                ->icon('tabler-file-type-pdf')
                ->url(fn(Model $record) => route('learning-resources.pdf', ['learningResource' => $record->id, 'isDownload' => 1])),

            Action::make('edit')
                ->label(__('learning/learningCertificate.form.edit'))
                ->color('gray')
                ->visible(function ($record) {
                    return Auth::user()->role_id < 3 ||
                        $record->user->role_id == 4 && $record->user->group?->instructors?->contains(Auth::id()) ||
                        $record->user->school?->created_by == Auth::id();
                })
                ->icon('tabler-eye-edit')
                ->url(fn(Model $record) => LearningCertificateResource::getUrl('edit', ['record' => $record->id])),
        ];
    }


    public function mount(int|string $record): void
    {
        $this->record = LearningCertificate::findOrFail($record);

        if ($this->record) {

            $isAdmin = Auth::user()->role_id < 3;
            $isTeacher = Auth::user()->role_id == 3 && $this->record->user->school_id == Auth::user()->school_id;
            $isOwner = $this->record->user_id == Auth::id();

            if (!$isAdmin && !$isTeacher && !$isOwner) {

                Notification::make()
                    ->title(__('learning/learningCertificate.you_dont_have_permissions'))
                    ->warning()
                    ->send();

                $this->redirectRoute('filament.app.pages.dashboard');
            }
        } else {

            Notification::make()
                ->title(__('learning/learningCertificate.something_went_wrong'))
                ->warning()
                ->send();

            $this->redirectRoute('filament.app.pages.dashboard');
        }
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
