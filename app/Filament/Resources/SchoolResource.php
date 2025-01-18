<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\School;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\SchoolResource\Pages;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\SchoolResource\Pages\CustomGroupPage;
use App\Filament\Resources\SchoolResource\RelationManagers\GroupsRelationManager;
use App\Filament\Resources\SchoolResource\RelationManagers\StudentsRelationManager;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

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
                Section::make('School Information')
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
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(200)
                            ->label('Full Name')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                            ->required(),
                        TextInput::make('address')
                            ->maxLength(200)
                            ->label('Address')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('city')
                            ->maxLength(200)
                            ->label('City')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('country')
                            ->maxLength(200)
                            ->label('Country')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('postal_code')
                            ->maxLength(200)
                            ->label('Postal Code')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
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
                            ->label('Phone Number')
                            ->prefixIcon('tabler-phone')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('website')
                            ->label('Website')
                            ->prefixIcon('tabler-globe')
                            ->required()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                    ]),
                Group::make([
                    Section::make('School Photo')
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
                                    if ($operation === 'create') {
                                        $lastSchool = School::latest('id')->first();
                                        return "schools/" . ($lastSchool ? $lastSchool->id + 1 : 1);
                                    }

                                    return "schools/$record->id";
                                })
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
                        'md' => 4,
                        'lg' => 4,
                    ]),

                Tabs::make()->columnSpanFull()->tabs([
                    Tab::make('Groups')
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(GroupsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                    Tab::make('Students')
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(StudentsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                ])->visible(fn(string $operation): bool => $operation !== 'create'),
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
                        return $record->address;
                    })
                    ->avatar(function ($record) {
                        return $record->avatar;
                    })
                    ->avatarType('image')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('postal_code')
                    ->label('Postal Code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(20)
                    ->tooltip(function ($record) {
                        return $record->email;
                    })
                    ->sortable(),
                TextColumn::make('website')
                    ->label('Website')
                    ->url(function ($record) {
                        return $record->website;
                    })
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),

            'edit-group' => CustomGroupPage::route('/{record}/edit-group'),
        ];
    }
}
