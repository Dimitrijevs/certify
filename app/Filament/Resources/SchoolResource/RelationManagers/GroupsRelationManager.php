<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\RelationManagers\RelationManager;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('institution.groups');
    }

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
                    ->label(__('group.name'))
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->required(),
                Select::make('teacher_id')
                    ->label(__('group.superviser'))
                    ->preload()
                    ->searchable()
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->options(User::where('school_id', $this->getOwnerRecord()->id)->get()->pluck('name', 'id')->toArray())
                    ->required(),
                Textarea::make('description')
                    ->label(__('group.description'))
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
                    ->label(__('group.name'))
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
                    ->label(__('group.superviser'))
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
                    ->label(__('group.workers_count'))
                    ->sortable()
                    ->searchable()
                    ->icon('tabler-users-group')
                    ->default(0)
                    ->formatStateUsing(function ($record) {
                        return $record->students->count();
                    }),
            ])
            ->filters([
                SelectFilter::make('teacher_id')
                    ->label(__('group.superviser'))
                    ->options(User::where('school_id', $this->getOwnerRecord()->id)->get()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('group.add_new_group'))
                    ->modalHeading(__('group.add_new_group')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(__('group.edit_group'))
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
                                ->title(__('group.you_have_been_removed_from_the_group'))
                                ->body(__('group.you_have_been_removed_from_the_group') . ' ' . $record->name)
                                ->warning()
                                ->sendToDatabase($users);

                            $livewire->dispatch('update-students-relation-manager');
                        }

                        Notification::make()
                            ->title(__('group.group_deleted'))
                            ->body(__('group.group') . ' ' . $record->name . ' ' . __('group.has_been_deleted'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading(__('group.delete_groups'))
                        ->modalDescription(__('group.are_you_sure_you_want_to_delete_these_groups'))
                        ->action(function (Collection $records, $livewire) {
                            foreach ($records as $group) {
                                $users = User::where('group_id', $group->id)->get();

                                foreach ($users as $user) {
                                    $user->group_id = null;
                                    $user->save();
                                }

                                $group->delete();

                                Notification::make()
                                    ->title(__('group.group_deleted'))
                                    ->body(__('group.group') . ' ' . $group->name . ' ' . __('group.has_been_deleted'))
                                    ->success()
                                    ->send();

                                Notification::make()
                                    ->title(__('group.you_have_been_removed_from_the_group'))
                                    ->body(__('group.you_have_been_removed_from_the_group') . ' ' . $group->name)
                                    ->warning()
                                    ->sendToDatabase($users);
                            }

                            $livewire->dispatch('update-students-relation-manager');
                        }),
                ]),
            ]);
    }
}
