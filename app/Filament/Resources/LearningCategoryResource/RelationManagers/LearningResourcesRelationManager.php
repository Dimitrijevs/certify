<?php

namespace App\Filament\Resources\LearningCategoryResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Resources\RelationManagers\RelationManager;
use Vendemy\Learning\Resources\LearningCategoryResource\Pages\CustomEditResource;
use App\Filament\Resources\LearningCategoryResource\Pages\ViewCustomLearningResource;

class LearningResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'learningResources';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('learning/learningResource.label_plural');
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('learning/learningResource.fields.name'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('learning/learningResource.fields.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->extraAttributes(['class' => 'text-center'])
                    ->sortable(),
                IconColumn::make('file_upload')
                    ->label(__('learning/learningResource.fields.file_upload'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->searchable()
                    ->sortable()
                    ->default(false),
                IconColumn::make('video_url')
                    ->label(__('learning/learningResource.fields.video'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->searchable()
                    ->sortable()
                    ->default(false),
                TextColumn::make('created_at')
                    ->label(__('learning/learningResource.fields.created_at'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload(),
                TernaryFilter::make('is_active')
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading(__('learning/learningResource.custom.new_resource'))
                    ->label(__('learning/learningResource.form.create')),
            ])
            ->actions([
                ActionGroup::make([
                    DeleteAction::make()
                        ->color('gray'),
                    ViewAction::make()
                        ->color('gray')
                        ->url(fn(Model $record) => ViewCustomLearningResource::getUrl(['record' => $record->id], isAbsolute: false)),
                ])->label('')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('gray')
                    ->button()
                    ->extraAttributes(['style' => 'padding-right: 0.15rem !important; padding-left: 0.425rem !important;']),
                EditAction::make()
                    ->label('')
                    ->tooltip(__('learning/learningResource.table.edit_resource'))
                    ->color('gray')
                    ->icon('tabler-arrow-right')
                    ->button()
                    ->url(fn(Model $record) => CustomEditResource::getUrl(['record' => $record->id], isAbsolute: false))
                    ->extraAttributes(['style' => 'padding-right: 0.2rem !important; padding-left: 0.35rem !important;']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->recordUrl(
                fn(Model $record): string => CustomEditResource::getUrl(['record' => $record->id], isAbsolute: false),
            );
    }
}
