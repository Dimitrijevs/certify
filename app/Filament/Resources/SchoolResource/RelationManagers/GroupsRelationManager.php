<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($livewire) {
                        $livewire->dispatch('update-students-relation-manager');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record, $livewire) {

                        $users = User::where('group_id', $record->id)->get();

                        if ($users) {
                            foreach ($users as $user) {

                                $user->group_id = null;
                                $user->save();
                            }

                            $record->delete();

                            Notification::make()
                                ->title('You have been removed from the group')
                                ->body('You have been removed from the group ' . $record->name)
                                ->warning()
                                ->sendToDatabase($users);

                            $livewire->dispatch('update-students-relation-manager');
                        }

                        Notification::make()
                            ->title('Group Deleted')
                            ->body('Group ' . $record->name . ' has been deleted')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Groups')
                        ->modalDescription('Are you sure you want to delete these groups?')
                        ->action(function (Collection $records, $livewire) {
                            foreach ($records as $group) {
                                $users = User::where('group_id', $group->id)->get();

                                foreach ($users as $user) {
                                    $user->group_id = null;
                                    $user->save();
                                }

                                $group->delete();

                                Notification::make()
                                    ->title('Group Deleted')
                                    ->body('Group ' . $group->name . ' has been deleted')
                                    ->success()
                                    ->send();

                                Notification::make()
                                    ->title('You have been removed from the group')
                                    ->body('You have been removed from the group ' . $group->name)
                                    ->warning()
                                    ->sendToDatabase($users);
                            }

                            $livewire->dispatch('update-students-relation-manager');
                        }),
                ]),
            ]);
    }
}
