<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Resources\RelationManagers\RelationManager;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->required(),
                Select::make('teacher_id')
                    ->label('Teacher')
                    ->preload()
                    ->searchable()
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->options(User::pluck('name', 'id')->toArray())
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
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
                AvatarWithDetails::make('name')
                    ->label('Name')
                    ->title(function ($record) {
                        return $record->name;
                    })
                    ->description(function ($record) {
                        return $record->description;
                    })
                    ->icon('tabler-users')
                    ->avatarType('icon')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                AvatarWithDetails::make('teacher_id')
                    ->label('Teacher')
                    ->title(function ($record) {
                        return $record->teacher->name;
                    })
                    ->description(function ($record) {
                        return $record->teacher->email;
                    })
                    ->avatar(function ($record) {
                        return $record->avatar;
                    })
                    ->avatarType('image')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('students')
                    ->label('Students Count')
                    ->sortable()
                    ->searchable()
                    ->icon('tabler-users-group')
                    ->default(0)
                    ->formatStateUsing(function ($record) {
                        return $record->students->count();
                    }),
            ])
            ->recordUrl(function ($record) {
                return route('filament.app.resources.schools.edit-group', ['record' => $record->id]);
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => route('filament.app.resources.schools.edit-group', ['record' => $record->id])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
