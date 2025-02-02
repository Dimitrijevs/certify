<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\RelationManagers\RelationManager;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

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
            ->columns([
                AvatarWithDetails::make('name')
                    ->label(__('institution.student'))
                    ->title(function ($record) {
                        return $record->name;
                    })
                    ->description(function ($record) {
                        return $record->email;
                    })
                    ->avatar(function ($record) {
                        return $record->avatar;
                    })
                    ->avatarType('image')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                AvatarWithDetails::make('group_id')
                    ->label(__('institution.groups'))
                    ->title(function ($record) {
                        if ($record->group) {
                            return $record->group->name;
                        } else {
                            return __('institution.no_group');
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->description(function ($record) {
                        return $record->group?->description;
                    })
                    ->icon('tabler-users')
                    ->avatarType('icon')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group_id')
                    ->label(__('institution.groups'))
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        return Group::where('school_id', $this->getOwnerRecord()->id)->pluck('name', 'id');
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(function () {
                        return '/app/users/create';
                    })
                    ->label(__('other.create_new_record')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return '/app/users/' . $record->id . '/edit';
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(function ($record) {
                return '/app/users/' . $record->id . '/edit';
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
