<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\School;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\SchoolResource\Pages;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\SchoolResource\RelationManagers\GroupsRelationManager;
use App\Filament\Resources\SchoolResource\RelationManagers\StudentsRelationManager;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationGroup = 'People';

    public static function getLabel(): string
    {
        return __('institution.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('institution.label_plural');
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::id() == $record->created_by || Auth::user()->role_id < 3;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->role_id < 3;
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
                Section::make(__('institution.institution_general_information'))
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
                        'lg' => 8,
                    ])
                    ->schema([
                        Hidden::make('created_by')
                            ->default(Auth::id()),
                            
                        TextInput::make('name')
                            ->maxLength(200)
                            ->label(__('institution.name'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                            ->required(),
                        TextInput::make('address')
                            ->maxLength(200)
                            ->label(__('institution.address'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('city')
                            ->maxLength(200)
                            ->label(__('institution.city'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('country')
                            ->maxLength(200)
                            ->label(__('institution.country'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('postal_code')
                            ->maxLength(200)
                            ->label(__('institution.postal_code'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('email')
                            ->label(__('institution.email_address'))
                            ->prefixIcon('tabler-mail')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('phone')
                            ->label(__('institution.phone_number'))
                            ->prefixIcon('tabler-phone')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('website')
                            ->label(__('institution.website'))
                            ->prefixIcon('tabler-globe')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        RichEditor::make('description')
                            ->label(__('learning/learningCategory.fields.description'))
                            ->nullable()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'h2',
                                'h3',
                                'codeBlock',
                            ])
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                    ]),
                Group::make([
                    Section::make(__('institution.photo'))
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
                        ->schema([
                            FileUpload::make('avatar')
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ])
                                ->directory(function ($record, $operation) {
                                    if ($operation == 'create') {
                                        $lastSchool = School::latest('id')->first();
                                        return "schools/" . ($lastSchool ? $lastSchool->id + 1 : 1);
                                    }

                                    return "schools/$record->id";
                                })
                                ->disk('public')
                                ->image()
                                ->imageEditor()
                                ->label('')
                                ->nullable(),
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
                        'lg' => 4,
                    ]),

                Tabs::make()->columnSpanFull()->tabs([
                    Tab::make(__('institution.groups'))
                        ->icon('tabler-users-group')
                        ->schema([
                            RelationManager::make()
                                ->manager(GroupsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                    Tab::make(__('institution.workers'))
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(StudentsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                ])->visible(fn(string $operation): bool => $operation !== 'create')
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AvatarWithDetails::make('name')
                    ->label(__('institution.label'))
                    ->title(function ($record) {
                        return $record->name;
                    })
                    ->description(function ($record) {
                        return $record->address;
                    })
                    ->avatar(function ($record) {
                        return $record->avatar;
                    })
                    ->tooltip(function ($record) {
                        return $record->name;
                    })
                    ->avatarType('image')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('institution.city'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('country')
                    ->label(__('institution.country'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('postal_code')
                    ->label(__('institution.postal_code'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label(__('institution.phone_number'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('institution.email_address'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(16)
                    ->tooltip(function ($record) {
                        return $record->email;
                    })
                    ->sortable(),
                TextColumn::make('website')
                    ->label(__('institution.website'))
                    ->url(function ($record) {
                        return $record->website;
                    })
                    ->default('Not Set')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color('primary')
                    ->tooltip(function ($record) {
                        return $record->website;
                    })
                    ->limit(20)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->url(function ($record) {
                        return 'schools/' . $record->id . '/view';
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
            
            'view-insitution' => Pages\InstitutionCustomView::route('/{record}/view'),
        ];
    }
}
