<?php

namespace App\Filament\Resources;

use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use App\Models\School;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Rules\MatchOldPassword;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use App\Livewire\ShowMyLearningList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\Group as FilaGroup;
use App\Forms\Components\CertificateRequirementForm;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'People';

    public static function getLabel(): string
    {
        return __('participants.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('participants.label_plural');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role_id < 3;
    }

    public static function canCreate(): bool
    {
        return Auth::user()->role_id < 3;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::id() == $record->id || Auth::user()->role_id == 3 && Auth::user()->school_id == $record->school_id && $record->role_id == 4 || Auth::user()->role_id < 3;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::id() == $record->id || Auth::user()->role_id < 3;
    }

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
                FilaGroup::make([
                    Section::make(__('participants.participant_general_information'))
                        ->schema([
                            TextInput::make('name')
                                ->maxLength(50)
                                ->label(__('participants.full_name'))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ])
                                ->required(),
                            TextInput::make('email')
                                ->label(__('institution.email_address'))
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
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 6,
                                ]),
                            Country::make('country')
                                ->label(__('user.fields.country'))
                                ->required()
                                ->preload()
                                ->searchable()
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 6,
                                ]),
                            Select::make('role_id')
                                ->label(__('participants.role'))
                                ->options(Role::where('id', '>', 1)->get()->pluck('name', 'id'))
                                ->required()
                                ->preload()
                                ->searchable()
                                ->visible(false)
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                            Select::make('school_id')
                                ->label(__('institution.label'))
                                ->live(debounce: 500)
                                ->options(function () {
                                    if (Auth::user()->role_id < 3) {
                                        return School::all()->pluck('name', 'id');
                                    } else if (Auth::user()->school_id && Auth::user()->role_id > 2) {
                                        return School::where('id', Auth::user()->school_id)->pluck('name', 'id');
                                    }

                                    return null;
                                })
                                ->searchable()
                                ->preload()
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 6,
                                ]),
                            Select::make('group_id')
                                ->live(debounce: 500)
                                ->label(__('participants.group'))
                                ->options(function ($get, $state) {
                                    if ($get('school_id') && Auth::user()->role_id < 3) {
                                        return Group::where('school_id', $get('school_id'))->pluck('name', 'id');
                                    } else if (Auth::user()->group_id && Auth::user()->role_id > 2) {
                                        return Group::where('id', Auth::user()->group_id)->pluck('name', 'id');
                                    }

                                    return null;
                                })
                                ->searchable()
                                ->preload()
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 6,
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
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Tabs::make('Tabs')
                        ->tabs([
                            Tab::make(__('user.requirements'))
                                ->icon('tabler-file-check')
                                ->visible(function ($operation) {
                                    return Auth::user()->group_id && $operation == 'edit';
                                })
                                ->schema([
                                    CertificateRequirementForm::make('info')
                                        ->nullable()
                                        ->dehydrated(false)
                                        ->columnSpanFull()
                                ]),
                            Tab::make(__('user.my_learning'))
                                ->icon('tabler-book')
                                ->schema([
                                    Livewire::make(ShowMyLearningList::class)
                                        ->label(label: '')
                                        ->dehydrated(false)
                                        ->columnSpanFull(),
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
                            'md' => 12,
                            'lg' => 12,
                        ])
                        ->persistTabInQueryString(),
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
                    Section::make(__('participants.avatar'))
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
                        ]),
                    Section::make(__('participants.password_change'))
                        ->schema([
                            TextInput::make('password_old')
                                ->label(__('participants.password'))
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
                                ->label(__('participants.new_password'))
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
                                ->label(__('participants.confirm_password'))
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
                    ->label(__('participants.name'))
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
                    ->label(__('institution.label'))
                    ->title(function ($record) {
                        if ($record->school) {
                            return $record->school->name;
                        } else {
                            return __('participants.no_institution');
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
                    ->label(__('participants.group'))
                    ->title(function ($record) {
                        if ($record->group) {
                            return $record->group->name;
                        } else {
                            return __('participants.no_group');
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
                    ->label(__('participants.role'))
                    ->title(function ($record) {
                        if ($record->role->name == 'Super Admin' || $record->role->name == 'Admin') {
                            return __('participants.admin');
                        } else if ($record->role->name == 'Student') {
                            return __('participants.student');
                        } else if ($record->role->name == 'Teacher') {
                            return __('participants.instructor');
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
                    ->label(__('institution.label'))
                    ->options(School::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('group_id')
                    ->label(__('participants.group'))
                    ->options(
                        Group::all()->mapWithKeys(function ($group) {
                            return [$group->id => $group->name . ' (' . $group->school?->name . ')'];
                        })
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('role_id')
                    ->label(__('participants.role'))
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
                    ->hidden(function ($record) {
                        return Auth::id() == $record->id;
                    }),
            ])
            ->bulkActions([
                // ...
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
