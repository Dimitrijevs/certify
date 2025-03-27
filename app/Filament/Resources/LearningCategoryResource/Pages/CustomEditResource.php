<?php

namespace App\Filament\Resources\LearningCategoryResource\Pages;

use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\LearningResource;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\LearningCategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Resources\LearningCategoryResource\Pages\ListLearningCategories;

class CustomEditResource extends EditRecord
{
    use InteractsWithRecord;

    protected static string $resource = LearningCategoryResource::class;

    public function mount(int|string $record): void
    {
        $this->record = LearningResource::findOrFail($record);
        $this->fillForm();
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label(__('learning/learningResource.form.view'))
                ->color('gray')
                ->icon('tabler-eye')
                ->url(LearningCategoryResource::getUrl('resource', ['record' => $this->record->id])),
            Action::make('save')
                ->label(__('learning/learningTest.form.save_changes'))
                ->action('save')
                ->icon('tabler-checkbox'),
            DeleteAction::make()
                ->after(function () {
                    return redirect(ListLearningCategories::getUrl());
                })
                ->icon('tabler-trash'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/' => __('learning/learningCategory.custom.home'),
            '/learning-categories' => __('learning/learningCategory.label_plural'),
            '/learning-categories/' . $this->record->category_id . '/edit' => $this->record->category->name,
            '/learning-categories/resource/' . $this->record->id => $this->record->name,
        ];
    }

    public function form(Form $form): Form
    {
        if ($form->getRecord()) {
            $id = $form->getRecord()->id;
        } else {
            $id = (LearningResource::latest()->first()->id ?? 0) + 1;
        }

        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make(__('learning/learningResource.form.section_title'))
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label(__('learning/learningResource.fields.name'))
                            ->required()
                            ->columnSpan([
                                'default' => 9,
                                'sm' => 10,
                                'md' => 10,
                                'lg' => 10,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningResource.fields.active'))
                            ->inline(False)
                            ->columnSpan([
                                'default' => 3,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                            ]),
                        RichEditor::make('description')
                            ->label(__('learning/learningResource.fields.description'))
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('learning_resources/' . $id)
                            ->nullable()
                            ->columnSpan(12)
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                            ]),
                        TextInput::make('video_url')
                            ->label(__('learning/learningResource.fields.video_url'))
                            ->nullable()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        Select::make('video_type')
                            ->label(__('learning/learningResource.fields.video_type'))
                            ->options([
                                'video/youtube' => 'Youtube',
                                'video/vimeo' => 'Vimeo',
                            ])
                            ->nullable()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        FileUpload::make('gallery')
                            ->label(__('learning/learningResource.fields.gallery'))
                            ->disk('public')
                            ->image()
                            ->multiple()
                            ->minSize(100)
                            ->maxSize(100000)
                            ->directory("learning_resources/$id")
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        FileUpload::make('file_upload')
                            ->label(__('learning/learningResource.fields.file_upload'))
                            ->multiple()
                            ->disk('public')
                            ->directory('learning_resources')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                    ])
            ]);
    }
}
