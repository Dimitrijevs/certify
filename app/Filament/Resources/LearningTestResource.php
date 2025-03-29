<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\CustomImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\LearningTestResource\Pages\ViewCustomTest;
use App\Filament\Resources\LearningTestResource\Pages\EditLearningTest;
use App\Filament\Resources\LearningTestResource\Pages\ViewLearningTest;
use App\Filament\Resources\LearningTestResource\Pages\ListLearningTests;
use App\Filament\Resources\LearningTestResource\Pages\CreateLearningTest;
use App\Filament\Resources\LearningTestResource\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\LearningTestResource\RelationManagers\RequirementsRelationManager;

class LearningTestResource extends Resource
{
    protected static ?string $model = LearningTest::class;

    protected static ?string $navigationGroup = 'Learning';

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function getNavigationGroup(): ?string
    // {
    //     return __('learning/learningCategory.group_label');
    // }

    public static function getLabel(): string
    {
        return 'Test';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tests';
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return Auth::user()->role_id < 3;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->role_id < 3;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->role_id < 3;
    }

    public static function form(Form $form): Form
    {
        if ($form->getRecord()) {
            $id = $form->getRecord()->id;
        } else {
            $lastRecord = LearningTest::latest('id')->first();
            $id = $lastRecord ? $lastRecord->id + 1 : 1;
        }

        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make(__('learning/learningTest.form.section_title'))
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->schema([
                        Hidden::make('created_by')
                            ->default(Auth::id()),
                            
                        TextInput::make('name')
                            ->label(__('learning/learningTest.fields.name'))
                            ->required()
                            ->columnSpan([
                                'default' => 8,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 6,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningTest.fields.active'))
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 2,
                            ])
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        Toggle::make('is_question_transition_enabled')
                            ->label(__('learning/learningTest.fields.free_navigation'))
                            ->columnSpan([
                                'default' => 5,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 2,
                            ])
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        Toggle::make('is_public')
                            ->label('Available for everyone')
                            ->columnSpan([
                                'default' => 7,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 2,
                            ])
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        TextInput::make('min_score')
                            ->label(__('learning/learningTest.fields.fault_points'))
                            ->live()
                            ->default(0)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->placeholder('12')
                            ->rules(function (?Model $record) {
                                $totalPoints = 0;
                                if ($record) {
                                    $details = $record->details->where('is_active', true);
                                    foreach ($details as $detail) {
                                        $totalPoints += $detail->points;
                                    }
                                }

                                return ['integer', 'max:' . $totalPoints];
                            })
                            ->suffix(function (?Model $record, $state) {
                                if ($record) {
                                    $details = $record->details->where('is_active', true);
                                    $totalPoints = 0;
                                    foreach ($details as $detail) {
                                        $totalPoints += $detail->points;
                                    }

                                    if (is_string($state) && preg_match('/[a-zA-Z]/', $state)) {
                                        return __('learning/learningTest.fields.score_to_pass') . ": {$totalPoints} / {$totalPoints}";
                                    } else if ($state >= $totalPoints) {
                                        return __('learning/learningTest.fields.score_to_pass') . ": {$totalPoints} / {$totalPoints}";
                                    }

                                    $pointsToPass = $totalPoints - $state;
                                    return __('learning/learningTest.fields.score_to_pass') . ": {$pointsToPass} / {$totalPoints}";
                                }

                                return null;
                            })
                            ->default(0)
                            ->required()
                            ->suffixIcon('tabler-award'),
                        TextInput::make('time_limit')
                            ->label('Time limit (minutes)')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->suffixIcon('tabler-clock')
                            ->placeholder('30')
                            ->minValue(5)
                            ->numeric(),
                        Group::make()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->columns([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                            ->schema([
                                Select::make('category_id')
                                    ->label(__('learning/learningCategory.label_plural'))
                                    ->relationship('category', 'name', function (Builder $query) {
                                        return $query->where('is_active', true);
                                    })
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan([
                                        'default' => 12,
                                        'sm' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ]),
                                Select::make('layout_id')
                                    ->label(__('learning/learningTest.fields.certificate_layout'))
                                    ->searchable()
                                    ->preload()
                                    ->relationship('layout', 'name')
                                    ->columnSpan([
                                        'default' => 12,
                                        'sm' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ]),
                                TextInput::make('cooldown')
                                    ->label(__('learning/learningTest.fields.cooldown'))
                                    ->columnSpan([
                                        'default' => 12,
                                        'sm' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ])
                                    ->suffixIcon('tabler-clock')
                                    ->tooltip('Cooldown in minutes between attempts')
                                    ->rules(['integer', 'min:0']),
                            ]),
                        FileUpload::make('thumbnail')
                            ->label(__('learning/learningTest.fields.thumbnail'))
                            ->image()
                            ->rules('image')
                            ->imageEditor()
                            ->imageResizeTargetWidth('711')
                            ->imageResizeTargetHeight('400')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->directory("learning_qualifications/$id")
                            ->disk('public')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('price')
                            ->label('Price')
                            ->live()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('discount')
                            ->label('Discount')
                            ->columnSpan([
                                'default' => 8,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 4,
                            ])
                            ->numeric()
                            ->suffixIcon('tabler-percentage')
                            ->minValue(0)
                            ->maxValue(100),
                        Toggle::make('available_for_everyone')
                            ->label('Available for everyone')
                            ->columnSpan([
                                'default' => 4,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 2,
                            ])
                            ->default(true)
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        RichEditor::make('description')
                            ->label(__('learning/learningTest.fields.description'))
                            ->columnSpan(12)
                            ->disableToolbarButtons([
                                'attachFiles',
                                'h2',
                                'h3',
                                'codeBlock',
                            ]),
                    ]),

                Tabs::make()->columnSpanFull()->tabs([
                    Tab::make(__('learning/learningTestDetails.label_plural'))
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(DetailsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),

                    Tab::make(__('learning/learningTestRequirements.label_plural'))
                        ->icon('tabler-exclamation-circle')
                        ->schema([
                            RelationManager::make()
                                ->manager(RequirementsRelationManager::class)
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
                Stack::make([
                    CustomImageColumn::make('thumbnail'),
                    TextColumn::make('name')
                        ->label('Name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),
                    TextColumn::make('description')
                        ->words(13)
                        ->markdown(),
                    TextColumn::make('price')
                        ->label('Price')
                        ->searchable()
                        ->formatStateUsing(function ($record) {
                            if ($record->price > 0 && $record->discount > 0) {
                                return $record->price . ' € - ' . $record->discount . ' % = ' . ($record->price - ($record->price * $record->discount / 100)) . ' €';
                            } else if ($record->price > 0 && $record->discount == 0) {
                                return $record->price . ' €';
                            } else {
                                return 'Free';
                            }
                        })
                        ->color(function ($record) {
                            if ($record->price > 0 && $record->discount > 0) {
                                return 'danger';
                            } else if ($record->price > 0 && $record->discount == 0) {
                                return 'primary';
                            } else {
                                return 'success';
                            }
                        })
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id > 2) {
                    // Basic requirements: must be active and public
                    $query->where('is_active', true)
                          ->where('is_public', true);
                    
                    // Then add conditions for either available_for_everyone OR same school_id
                    $query->where(function($subQuery) {
                        // Either available for everyone
                        $subQuery->where('available_for_everyone', true);
                        
                        // OR created by someone from the same school (if user has a school)
                        if (Auth::user()->school_id) {
                            $subQuery->orWhereHas('createdBy', function($userQuery) {
                                $userQuery->where('school_id', Auth::user()->school_id);
                            });
                        }
                    });
                    
                    return $query;
                }
            
                return $query;
            })
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->columnSpan(1)
                    ->native(false)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                TernaryFilter::make('is_public')
                    ->label('Public')
                    ->native(false)
                    ->columnSpan(1)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                Filter::make('is_free')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
                        Select::make('is_free')
                            ->live()
                            ->options([
                                true => 'Free',
                                false => 'Paid',
                            ])
                            ->columnSpan(2)
                            ->label('Price')
                            ->native(false),
                        TextInput::make('price_from')
                            ->label('Price From')
                            ->numeric()
                            ->live()
                            ->visible(function ($get) {
                                $isFree = $get('is_free');
                                return $isFree !== null && $isFree == false;
                            })
                            ->columnSpan(1)
                            ->minValue(0),
                        TextInput::make('price_to')
                            ->label('Price To')
                            ->numeric()
                            ->live()
                            ->columnSpan(1)
                            ->visible(function ($get) {
                                $isFree = $get('is_free');
                                return $isFree !== null && $isFree == false;
                            })
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['is_free'])) {
                            return $query;
                        }
                    
                        if ($data['is_free'] == true) {
                            return $query->where(function ($query) {
                                $query->where('price', 0)
                                    ->orWhereNull('price');
                            });
                        } else {
                            // Base query for paid items
                            $query->where('price', '>', 0);
                            
                            // Apply price range filters if set, considering discount
                            if (!empty($data['price_from'])) {
                                $query->where(function ($query) use ($data) {
                                    // Either the original price meets the criteria
                                    $query->where('price', '>=', $data['price_from'])
                                        // OR the discounted price meets the criteria
                                        ->orWhereRaw('(price - (price * discount / 100)) >= ?', [$data['price_from']]);
                                });
                            }
                            
                            if (!empty($data['price_to'])) {
                                $query->where(function ($query) use ($data) {
                                    // Either the original price meets the criteria
                                    $query->where('price', '<=', $data['price_to'])
                                        // OR the discounted price meets the criteria
                                        ->orWhereRaw('(price - (price * discount / 100)) <= ?', [$data['price_to']]);
                                });
                            }
                            
                            // If both from and to are set, we already applied the constraints above
                            
                            return $query;
                        }
                    }),
            ])
            ->filtersFormColumns(2)
            ->filtersFormWidth(MaxWidth::TwoExtraLarge)
            ->actions([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id == 1) {
                    return $query; // Admin sees all tests
                } elseif (Auth::user()->role_id >= 2) {
                    // Start with active tests requirement
                    $query->where('is_active', true);
                    
                    // Create a nested where condition for public OR same school
                    $query->where(function ($subquery) {
                        // Public tests
                        $subquery->where('is_public', true);
                        
                        // OR tests created by users from the same school
                        if (Auth::user()->school_id) {
                            $subquery->orWhereHas('createdBy', function ($userQuery) {
                                $userQuery->where('school_id', Auth::user()->school_id);
                            });
                        }
                    });
                }
                
                return $query;
            })
            ->bulkActions([
                //
            ])
            ->recordUrl(
                fn(Model $record) => ViewCustomTest::getUrl(['record' => $record->id], isAbsolute: false)
            );
    }

    public static function getRelations(): array
    {
        return [
            // ...
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearningTests::route('/'),
            'create' => CreateLearningTest::route('/create'),
            'edit' => EditLearningTest::route('/{record}/edit'),
            'view' => ViewLearningTest::route('/{record}'),

            'viewTest' => ViewCustomTest::route('/{record}/view'),
        ];
    }
}
