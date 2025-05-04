<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Filament\Notifications\Actions\Action as NotificationAction;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    #[On('update-students-relation-manager')]
    public function refresh()
    {
        // Refresh logic here
    }

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return __('institution.workers');
    }

    public function canEdit(Model $record): bool
    {
        return Auth::user()->role_id < 3;
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
            ->defaultGroup('group.name')
            ->columns([
                AvatarWithDetails::make('name')
                    ->label(__('worker.worker'))
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
                TextColumn::make('role_id')
                    ->label('Teacher')
                    ->formatStateUsing(function ($state) {
                        return $state === 3 ? 'Teacher' : 'Student';
                    }),
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
                Tables\Actions\Action::make('add_user')
                    ->closeModalByClickingAway(false)
                    ->form([
                        Grid::make([
                            'default' => 12,
                            'sm' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ])->schema([
                                    Select::make('user_id')
                                        ->live()
                                        ->label(__('worker.worker'))
                                        ->options(function () {
                                            return User::where('role_id', 4)
                                                ->where('school_id', null)
                                                ->pluck('name', 'id');
                                        })
                                        ->columnSpan([
                                            'default' => 12,
                                            'sm' => 12,
                                            'md' => 12,
                                            'lg' => 12,
                                        ])
                                        ->preload()
                                        ->required()
                                        ->searchable(),
                                    Select::make('group_id')
                                        ->label(__('worker.group'))
                                        ->options(function () {
                                            return Group::where('school_id', $this->getOwnerRecord()->id)
                                                ->pluck('name', 'id');
                                        })
                                        ->visible(function ($get) {
                                            return $get('user_id') !== null;
                                        })
                                        ->columnSpan([
                                            'default' => 8,
                                            'sm' => 9,
                                            'md' => 9,
                                            'lg' => 10,
                                        ])
                                        ->preload()
                                        ->required()
                                        ->searchable(),
                                    Toggle::make('is_teacher')
                                        ->label('Teacher')
                                        ->inline(false)
                                        ->columnSpan([
                                            'default' => 4,
                                            'sm' => 3,
                                            'md' => 3,
                                            'lg' => 2,
                                        ])
                                        ->visible(function ($get) {
                                            return $get('user_id');
                                        })
                                        ->onIcon('tabler-check')
                                        ->offIcon('tabler-x'),

                                ])
                    ])
                    ->mutateFormDataUsing(function (array $data) {
                        $recipient = User::find($data['user_id']);

                        $group = Group::find($data['group_id']);

                        if ($data['is_teacher']) {
                            $title = __('worker.institution_owner') . ': ' . $this->getOwnerRecord()->creator->name . ' ' . __('worker.invited_you_to_join_their_institution') . ' ' . $this->getOwnerRecord()->name . ' as a teacher to the group ' . $group->name;
                        } else {
                            $title = __('worker.institution_owner') . ': ' . $this->getOwnerRecord()->creator->name . ' ' . __('worker.invited_you_to_join_their_institution') . ' ' . $this->getOwnerRecord()->name . ' as a student to the group ' . $group->name;
                        }

                        Notification::make()
                            ->info()
                            ->title($title)
                            ->actions([
                                NotificationAction::make('accept')
                                    ->url(route('accept-invite', [
                                        'institution' => $this->getOwnerRecord()->id,
                                        'group' => $data['group_id'],
                                        'is_teacher' => $data['is_teacher'],
                                        'sender' => Auth::user()->id,
                                    ]))
                                    ->close()
                                    ->button()
                                    ->color('primary')
                                    ->icon('tabler-check'),
                                NotificationAction::make('decline')
                                    ->url(route('reject-invite', [
                                        'institution' => $this->getOwnerRecord()->id,
                                        'group' => $data['group_id'],
                                        'is_teacher' => $data['is_teacher'],
                                        'sender' => Auth::user()->id,
                                    ]))
                                    ->close()
                                    ->button()
                                    ->color('danger')
                                    ->icon('tabler-x'),
                            ])
                            ->sendToDatabase($recipient);

                        Notification::make()
                            ->title(__('worker.invite_sent'))
                            ->success()
                            ->send();

                        return $data;
                    })
                    ->label(__('worker.add_new_worker')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(__('worker.edit_worker'))
                    ->form(function ($record) {
                        return [
                            Grid::make([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])->schema([
                                        Select::make('id')
                                            ->live()
                                            ->label(__('worker.worker'))
                                            ->options(function () {
                                                return User::whereIn('role_id', [3, 4])
                                                    ->where('school_id', $this->getOwnerRecord()->id)
                                                    ->pluck('name', 'id') ?? [];
                                            })
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('group_id', null);
                                            })
                                            ->default($record ? $record->id : null)
                                            ->columnSpan([
                                                'default' => 12,
                                                'sm' => 12,
                                                'md' => 12,
                                                'lg' => 12,
                                            ])
                                            ->disabled()
                                            ->preload()
                                            ->required()
                                            ->searchable(),
                                        Select::make('group_id')
                                            ->label(__('worker.group'))
                                            ->options(function () {
                                                return Group::where('school_id', $this->getOwnerRecord()->id)
                                                    ->pluck('name', 'id') ?? []; // Add null check
                                            })
                                            ->visible(function ($get) {
                                                return $get('id') !== null;
                                            })
                                            ->default($record && $record->group_id ? $record->group_id : null) // Add null check
                                            ->columnSpan([
                                                'default' => 8,
                                                'sm' => 9,
                                                'md' => 9,
                                                'lg' => 10,
                                            ])
                                            ->preload()
                                            ->required()
                                            ->searchable(),
                                        Toggle::make('is_teacher')
                                            ->label('Teacher')
                                            ->inline(false)
                                            ->columnSpan([
                                                'default' => 4,
                                                'sm' => 3,
                                                'md' => 3,
                                                'lg' => 2,
                                            ])
                                            ->afterStateHydrated(function ($set, $record) {
                                                $set('is_teacher', $record->role_id === 3);
                                            })
                                            ->visible(function ($get) {
                                                return $get('id');
                                            })
                                            ->onIcon('tabler-check')
                                            ->offIcon('tabler-x'),
                                    ])
                        ];
                    })
                    ->using(function (array $data, $record) {

                        if ($record->group_id != $data['group_id'] || $record->role_id != ($data['is_teacher'] ? 3 : 4)) {

                            $recipient = User::find($record->id);

                            $group = Group::find($data['group_id']);

                            if ($data['is_teacher']) {
                                $title = __('worker.institution_owner') . ': ' . $this->getOwnerRecord()->creator->name . ' ' . __('worker.invited_you_to_join_their_institution') . ' ' . $this->getOwnerRecord()->name . ' as a teacher to the group ' . $group->name;
                            } else {
                                $title = __('worker.institution_owner') . ': ' . $this->getOwnerRecord()->creator->name . ' ' . __('worker.invited_you_to_join_their_institution') . ' ' . $this->getOwnerRecord()->name . ' as a student to the group ' . $group->name;
                            }

                            Notification::make()
                                ->info()
                                ->title($title)
                                ->actions([
                                    NotificationAction::make('accept')
                                        ->url(route('accept-invite', [
                                            'institution' => $this->getOwnerRecord()->id,
                                            'group' => $data['group_id'],
                                            'is_teacher' => $data['is_teacher'],
                                            'sender' => Auth::user()->id,
                                        ]))
                                        ->close()
                                        ->button()
                                        ->color('primary')
                                        ->icon('tabler-check'),
                                    NotificationAction::make('decline')
                                        ->url(route('reject-invite', [
                                            'institution' => $this->getOwnerRecord()->id,
                                            'group' => $data['group_id'],
                                            'is_teacher' => $data['is_teacher'],
                                            'sender' => Auth::user()->id,
                                        ]))
                                        ->close()
                                        ->button()
                                        ->color('danger')
                                        ->icon('tabler-x'),
                                ])
                                ->sendToDatabase($recipient);

                            Notification::make()
                                ->title('Worker Invite Resent')
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record = User::find($record->id);
                        $record->school_id = null;
                        $record->group_id = null;
                        $record->role_id = 4;
                        $record->save();

                        Notification::make()
                            ->title(__('worker.worker_removed'))
                            ->success()
                            ->send();

                        Notification::make()
                            ->info()
                            ->title(__('worker.institution_owner') . ': ' . $this->getOwnerRecord()->creator->name . ' ' . __('worker.removed_you_from_the_institution') . ' ' . $this->getOwnerRecord()->name)
                            ->sendToDatabase($record);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('worker.remove_worker'))
                    ->modalDescription(__('worker.are_you_sure_you_want_to_remove_this_worker'))
                    ->color('danger')
                    ->icon('tabler-trash')
                    ->label(__('Delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
