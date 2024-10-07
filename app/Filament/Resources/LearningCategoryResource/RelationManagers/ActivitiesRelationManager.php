<?php

namespace App\Filament\Resources\LearningCategoryResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningResource;
use App\Tables\Columns\ToHumanTime;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\LearningCategoryResource;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\LearningCategoryResource\Pages\ListLearningCategories;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('learning/learningResourceActivity.label_plural');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                AvatarWithDetails::make('user.name')
                    ->label(__('user.label'))
                    ->title(function ($record) {
                        return $record->user->name;
                    })
                    ->description(function ($record) {
                        return $record->user->job_title;
                    })
                    ->avatar(function (?Model $record) {
                        return $record->user?->avatar;
                    })
                    ->avatarType('image')
                    ->marginStart()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('resource.name')
                    ->label(__('learning/learningResource.label'))
                    ->searchable()
                    ->sortable()
                    ->default('Deleted resource')
                    ->words(3)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->url(function ($record) {
                        if (!is_null($record->resource) && $record->resource->where('is_active', true)) {
                            return LearningCategoryResource::getUrl('resource', ['record' => $record->resource->id]);
                        }

                        return ListLearningCategories::getUrl();
                    }),
                ToHumanTime::make('time_spent')
                    ->label(__('learning/learningResourceActivity.table.time_spent')),
                ToHumanTime::make('video_watched')
                    ->label(__('learning/learningResourceActivity.table.video_watched')),
                ToHumanTime::make('video_progress')
                    ->label(__('learning/learningResourceActivity.table.video_progress')),
                ToHumanTime::make('video_duration')
                    ->label(__('learning/learningResourceActivity.table.video_duration')),
                TextColumn::make('created_at')
                    ->label(__('learning/learningResourceActivity.table.created_at'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('resource_id')
                    ->multiple()
                    ->options(function () {
                        return LearningResource::all()->pluck('name', 'id');
                    })
                    ->preload(),
                SelectFilter::make(name: 'user_id')
                    ->multiple()
                    ->options(function () {
                        return User::all()->pluck('name', 'id');
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
