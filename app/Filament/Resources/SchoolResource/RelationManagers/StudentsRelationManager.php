<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
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
        return 'Workers';
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
                    ->label('Worker')
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
                                        ->label('Worker')
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
                                        ->label('Group')
                                        ->options(function () {
                                            return Group::where('school_id', $this->getOwnerRecord()->id)
                                                ->pluck('name', 'id');
                                        })
                                        ->visible(function ($get) {
                                            return $get('user_id') !== null;
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
                                ])
                    ])
                    ->mutateFormDataUsing(function (array $data) {
                        $recipient = User::find($data['user_id']);

                        Notification::make()
                            ->info()
                            ->title('Institotion owner: ' . $this->getOwnerRecord()->creator->name . ' invited you to join the institution')
                            ->actions([
                                NotificationAction::make('accept')
                                    ->url(route('accept-invite', [
                                        'institution' => $this->getOwnerRecord()->id,
                                        'group' => $data['group_id'],
                                        'sender' => Auth::user()->id,
                                    ]))
                                    ->button()
                                    ->color('primary')
                                    ->icon('tabler-check'),
                                NotificationAction::make('decline')
                                    ->url(route('reject-invite', [
                                        'institution' => $this->getOwnerRecord()->id,
                                        'group' => $data['group_id'],
                                        'sender' => Auth::user()->id,
                                    ]))
                                    ->button()
                                    ->color('danger')
                                    ->icon('tabler-x'),
                            ])
                            ->sendToDatabase($recipient);

                        Notification::make()
                            ->title('Invite sent')
                            ->success()
                            ->send();

                        return $data;
                    })
                    ->label('Add Worker'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
                                            ->label('Worker')
                                            ->options(function () {
                                                return User::where('role_id', 4)
                                                    ->where('school_id', $this->getOwnerRecord()->id)
                                                    ->pluck('name', 'id') ?? []; // Add null check here
                                            })
                                            ->default($record ? $record->id : null) // Add null check
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
                                            ->label('Group')
                                            ->options(function () {
                                                return Group::where('school_id', $this->getOwnerRecord()->id)
                                                    ->pluck('name', 'id') ?? []; // Add null check
                                            })
                                            ->visible(function ($get) {
                                                return $get('id') !== null;
                                            })
                                            ->default($record && $record->group_id ? $record->group_id : null) // Add null check
                                            ->columnSpan([
                                                'default' => 12,
                                                'sm' => 12,
                                                'md' => 12,
                                                'lg' => 12,
                                            ])
                                            ->preload()
                                            ->required()
                                            ->searchable(),
                                    ])
                        ];
                    })
                    ->using(function (array $data) {
                        $recipient = User::find($data['id']);

                        Notification::make()
                            ->info()
                            ->title('Institotion owner: ' . $this->getOwnerRecord()->creator->name . ' invited you to join the institution ' . $this->getOwnerRecord()->name)
                            ->actions([
                                NotificationAction::make('accept')
                                    ->url(route('accept-invite', [
                                        'institution' => $this->getOwnerRecord()->id,
                                        'group' => $data['group_id'],
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
                                        'sender' => Auth::user()->id,
                                    ]))
                                    ->close()
                                    ->button()
                                    ->color('danger')
                                    ->icon('tabler-x'),
                            ])
                            ->sendToDatabase($recipient);

                        Notification::make()
                            ->title('Invite sent')
                            ->body('Invite sent to ' . $recipient->name)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record = User::find($record->id);
                        $record->school_id = null;
                        $record->group_id = null;
                        $record->save();

                        Notification::make()
                            ->title('Worker removed')
                            ->success()
                            ->send();

                        Notification::make()
                            ->info()
                            ->title('Institotion owner: ' . $this->getOwnerRecord()->creator->name . ' removed you from the institution')
                            ->sendToDatabase($record);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Remove Worker')
                    ->modalDescription('Are you sure you want to remove this worker?')
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
