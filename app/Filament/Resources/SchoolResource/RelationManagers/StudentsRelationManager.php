<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
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

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return 'Workers';
    }

    public function canEdit(Model $record): bool
    {
        return Auth::id() == $record->id || Auth::user()->role_id == 3 && Auth::user()->school_id == $record->school_id && $record->role_id == 4;
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
                                        ->where('role_id', 4)
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
                    ->after(function (array $data) {
                        $user = User::find($data['user_id']);
                        $user->school_id = $this->getOwnerRecord()->id;
                        $user->group_id = $data['group_id'];
                        $user->save();

                        Notification::make()
                            ->title('Worker Added Successfully')
                            ->success()
                            ->send();
                    })
                    ->label('Add Worker'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return '/app/users/' . $record->id . '/edit';
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            // ->recordUrl(function ($record) {
            //     return '/app/users/' . $record->id . '/edit';
            // })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
