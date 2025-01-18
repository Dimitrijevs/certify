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
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\Group as FilaGroup;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Users';

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
                //
            ])
            ->filters([
                //
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
