<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use App\Models\School;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Rules\MatchOldPassword;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use Filament\Forms\Components\Group as FilaGroup;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'People';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(50)
                            ->label('Full Name')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->prefixIcon('tabler-mail')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(function ($record, $operation) {
                                if ($operation != 'create') {
                                    if ($record->id == Auth::id()) {
                                        Session::flush();

                                        return redirect('/app/login');
                                    }
                                }
                            })
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        Select::make('school_id')
                            ->label('School')
                            ->live()
                            ->options(School::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        Select::make('group_id')
                            ->label('Group')
                            ->options(function ($get) {
                                if ($get('school_id')) {
                                    return Group::where('school_id', $get('school_id'))->pluck('name', 'id');
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                    ])
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 8,
                        'lg' => 8,
                    ]),
                FilaGroup::make([
                    Section::make('Password Change')
                        ->schema([
                            TextInput::make('password_old')
                                ->label('Old Password')
                                ->password()
                                ->revealable()
                                ->prefixIcon('tabler-lock')
                                ->requiredWith('password, password_confirmation')
                                ->dehydrated(false)
                                ->rules(['min:8', 'regex:/^\S*$/', new MatchOldPassword])
                                ->hidden(fn(string $context, $record): bool => $context === 'create' || Auth::id() !== $record->id)
                                ->afterStateUpdated(function ($record, $operation, $get, $state) {
                                    $passwordConfirmation = $get('password_confirmation');
                                    $oldPassword = $state;
                                    $password = $get('password');

                                    if (
                                        $operation != 'create'
                                        && $record->id == Auth::id()
                                        && $passwordConfirmation == $password
                                        && strlen($password) >= 8
                                        && strlen($passwordConfirmation) >= 8
                                        && $password !== ''
                                        && $password !== null
                                        && $password != $oldPassword
                                        && password_verify($oldPassword, $record->password)
                                    ) {
                                        Session::flush();
                                        return redirect('/app/login');
                                    }
                                })
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                            TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->prefixIcon('tabler-lock-plus')
                                ->requiredWith('password_old, password_confirmation')
                                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                ->dehydrated(fn($state) => filled($state))
                                ->required(function ($context): bool {
                                    return $context === 'create';
                                })
                                ->afterStateUpdated(function ($record, $operation, $get, $state, $context) {
                                    $passwordConfirmation = $get('password_confirmation');
                                    $oldPassword = $get('password_old');

                                    if (
                                        $operation != 'create'
                                        && $record->id == Auth::id()
                                        && $passwordConfirmation == $state
                                        && strlen($state) >= 8
                                        && strlen($passwordConfirmation) >= 8
                                        && $state !== ''
                                        && $state !== null
                                        && $state != $oldPassword
                                        && password_verify($oldPassword, $record->password)
                                    ) {
                                        Session::flush();
                                        return redirect('/app/login');
                                    }
                                })
                                ->confirmed()
                                ->different('password_old')
                                ->rules(['confirmed', 'min:8', 'regex:/^\S*$/'])
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                            TextInput::make('password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->required(function ($context): bool {
                                    return $context === 'create';
                                })
                                ->revealable()
                                ->prefixIcon('tabler-lock-exclamation')
                                ->requiredWith('password, password_old')
                                ->dehydrated(false)
                                ->rules(['min:8', 'regex:/^\S*$/'])
                                ->required(fn(string $context): bool => $context === 'create')
                                ->afterStateUpdated(function ($record, $operation, $get, $state) {
                                    $passwordConfirmation = $state;
                                    $oldPassword = $get('password_old');
                                    $password = $get('password');

                                    if (
                                        $operation != 'create'
                                        && $record->id == Auth::id()
                                        && $passwordConfirmation == $password
                                        && strlen($password) >= 8
                                        && strlen($passwordConfirmation) >= 8
                                        && $password !== ''
                                        && $password !== null
                                        && $password != $oldPassword
                                        && password_verify($oldPassword, $record->password)
                                    ) {
                                        Session::flush();
                                        return redirect('/app/login');
                                    }
                                })
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                        ]),
                    Section::make('Avatar Upload')
                        ->schema([
                            FileUpload::make('avatar')
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ])
                                ->directory(function ($record, $operation) {
                                    if ($operation === 'create') {
                                        $lastUser = User::latest('id')->first();
                                        return "avatars/" . ($lastUser ? $lastUser->id + 1 : 1);
                                    }

                                    return "avatars/$record->id";
                                })
                                ->image()
                                ->imageEditor()
                                ->label('')
                                ->nullable(),
                        ])
                ])
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 4,
                        'lg' => 4,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AvatarWithDetails::make('name')
                    ->label('Name')
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
                AvatarWithDetails::make('school_id')
                    ->label('School')
                    ->title(function ($record) {
                        if ($record->school) {
                            return $record->school->name;
                        } else {
                            return 'No School';
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->description(function ($record) {
                        return $record->school?->address;
                    })
                    ->avatar(function ($record) {
                        return $record->school?->avatar;
                    })
                    ->avatarType('image')
                    ->searchable()
                    ->sortable(),
                AvatarWithDetails::make('group_id')
                    ->label('Group')
                    ->title(function ($record) {
                        if ($record->group) {
                            return $record->group->name;
                        } else {
                            return 'No Group';
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
                AvatarWithDetails::make('role_id')
                    ->label('Role')
                    ->title(function ($record) {
                        if ($record->role) {
                            if ($record->role->name == 'super_admin' || $record->role->name == 'admin') {
                                return 'Admin';
                            } else if ($record->role->name == 'student') {
                                return 'Student';
                            } else if ($record->role->name == 'teacher') {
                                return 'Teacher';
                            } else {
                                return $record->role->name;
                            }
                        } else {
                            return 'No Role';
                        }
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->icon('tabler-shield')
                    ->avatarType('icon')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('school_id')
                    ->label('School')
                    ->options(School::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('group_id')
                    ->label('Group')
                    ->options(Group::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('role_id')
                    ->label('Role')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
