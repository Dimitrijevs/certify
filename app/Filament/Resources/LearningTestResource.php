<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
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
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Forms\Components\ShowCertificateTemplate;
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

    public static function getLabel(): string
    {
        return __('learning/learningTest.fields.test');
    }

    public static function getPluralModelLabel(): string
    {
        return __('learning/learningTest.fields.tests');
    }

    public static function canView(Model $record): bool
    {
        return true;
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
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 6,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningTest.fields.active'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 2,
                            ])
                            ->default(true)
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        Toggle::make('is_question_transition_enabled')
                            ->label(__('learning/learningTest.fields.free_navigation'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 4,
                                'md' => 4,
                                'lg' => 2,
                            ])
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        Toggle::make('available_for_everyone')
                            ->label(__('learning/learningTest.fields.available_for_everyone'))
                            ->columnSpan([
                                'default' => 12,
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
                            ->disabled(function ($operation) {
                                return $operation === 'create';
                            })
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
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->suffixIcon('tabler-award'),
                        TextInput::make('time_limit')
                            ->label(__('learning/learningTest.fields.time_limit_minutes'))
                            ->live()
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
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('cooldown')
                            ->label(__('learning/learningTest.fields.cooldown'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->suffixIcon('tabler-clock')
                            ->tooltip(__('learning/learningTest.fields.cooldown_in_minutes_between_attempts'))
                            ->rules(['integer', 'min:0']),
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
                                ShowCertificateTemplate::make('layout_id')
                                    ->label(__('learning/learningTest.fields.certificate_layout'))
                                    ->columnSpan([
                                        'default' => 12,
                                        'sm' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ]),
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
                            ->label(__('learning/learningTest.fields.price'))
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
                            ->label(__('learning/learningTest.fields.discount'))
                            ->live()
                            ->prefixIcon('tabler-percentage')
                            ->disabled(function ($get) {
                                return $get('price') == 0 || $get('price') == null;
                            })
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Select::make('currency_id')
                            ->label(__('learning/learningTest.fields.currency'))
                            ->preload()
                            ->live()
                            ->searchable()
                            ->disabled(function ($get) {
                                return $get('price') == 0 || $get('price') == null;
                            })
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->options(function () {
                                return Currency::all()
                                    ->mapWithKeys(function ($currency) {
                                        return [$currency->id => $currency->name . ' (' . $currency->symbol . ')'];
                                    });
                            })
                            ->suffix(function ($get, $state) {
                                $discount = $get('discount');

                                if ($discount > 0) {
                                    $price = $get('price');
                                    $symbol = Currency::find($state)->symbol ?? '€';

                                    if ($price == 0 || $price == null) {
                                        return __('learning/learningTest.fields.free');
                                    }

                                    return $price . ' ' . $symbol . ' - ' . $discount . ' % = ' . ($price - ($price * $discount / 100)) . ' ' . $symbol;
                                }
                            }),
                        Select::make('language_id')
                            ->label(__('learning/learningTest.fields.language'))
                            ->options(function () {
                                return Language::all()
                                    ->mapWithKeys(function ($lang) {
                                        return [$lang->id => $lang->name . ' (' . $lang->iso2 . ', ' . $lang->iso3 . ')'];
                                    });
                            })
                            ->prefixIcon('tabler-globe')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        Select::make('categories')
                            ->label(__('learning/learningTest.fields.categories'))
                            ->options(Category::all()->pluck('name', 'id'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
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
                        ->visible(function ($record) {
                            if (!$record) {
                                return false;
                            }

                            return LearningTest::canUserEdit($record->id);
                        })
                        ->schema([
                            RelationManager::make()
                                ->manager(RequirementsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                ])->visible(function (string $operation) {
                    return $operation == 'edit';
                })
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([6, 18, 30, 60, 99])
            ->defaultPaginationPageOption(30)
            ->columns([
                Stack::make([
                    CustomImageColumn::make('thumbnail')
                        ->categories(function ($record) {
                            return $record->categories;
                        })
                        ->languageName(function ($record) {
                            return $record->language->name;
                        }),
                    TextColumn::make('name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),
                    TextColumn::make('description')
                        ->words(13)
                        ->markdown(),
                    TextColumn::make('price')
                        ->searchable()
                        ->formatStateUsing(function ($record) {
                            if ($record->price > 0 && $record->discount > 0) {
                                return $record->price . ' ' . $record->currency?->symbol . ' - ' . $record->discount . ' % = ' . ($record->price - ($record->price * $record->discount / 100)) . ' ' . $record->currency?->symbol;
                            } else if ($record->price > 0 && $record->discount == 0) {
                                return $record->price . ' ' . $record->currency?->symbol;
                            } else {
                                return __('learning/learningTest.fields.free');
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
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->filters([
                Filter::make('my_tests')
                    ->columns(1)
                    ->columnSpan(1)
                    ->form([
                        Select::make('my_tests')
                            ->label(__('learning/learningTest.my_tests'))
                            ->options([
                                true => __('other.yes'),
                                false => __('other.no'),
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if (!$data['my_tests']) {
                            return $query;
                        }

                        return $query->where('created_by', Auth::id());
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (empty($data) || $data['my_tests'] === null || $data['my_tests'] == false) {
                            return null;
                        }

                        return __('learning/learningTest.my_tests');
                    }),
                Filter::make('my_purchased_tests')
                    ->columns(1)
                    ->columnSpan(1)
                    ->form([
                        Select::make('my_purchased_tests')
                            ->label(__('learning/learningTest.my_purchased_tests'))
                            ->options([
                                true => __('other.yes'),
                                false => __('other.no'),
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if (!$data['my_purchased_tests']) {
                            return $query;
                        }

                        return $query->whereHas('purchases', function (Builder $subQuery) {
                            $subQuery->where('user_id', Auth::id());
                        });
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (empty($data) || $data['my_purchased_tests'] === null || $data['my_purchased_tests'] == false) {
                            return null;
                        }

                        return __('learning/learningTest.my_purchased_tests');
                    }),
                TernaryFilter::make('is_active')
                    ->label(__('learning/learningTest.fields.active'))
                    ->columnSpan(1)
                    ->native(false)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                TernaryFilter::make('is_public')
                    ->label(__('learning/learningTest.fields.public'))
                    ->native(false)
                    ->columnSpan(1)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                SelectFilter::make('language_id')
                    ->label(__('learning/learningTest.fields.language'))
                    ->columnSpan(1)
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        return Language::all()
                            ->mapWithKeys(function ($lang) {
                                return [$lang->id => $lang->name . ' (' . $lang->iso2 . ', ' . $lang->iso3 . ')'];
                            });
                    }),
                Filter::make('category')
                    ->form([
                        Select::make('category_ids')
                            ->label(__('learning/learningTest.fields.categories'))
                            ->preload()
                            ->searchable()
                            ->multiple()
                            ->options(function () {
                                return Category::all()->pluck('name', 'id');
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['category_ids'],
                            function (Builder $query, $categoryIds) {
                                return $query->where(function (Builder $query) use ($categoryIds) {
                                    foreach ($categoryIds as $categoryId) {
                                        $query->orWhereJsonContains('categories', $categoryId);
                                    }
                                });
                            }
                        );
                    }),
                SelectFilter::make('currency_id')
                    ->label(__('learning/learningTest.fields.currency'))
                    ->preload()
                    ->searchable()
                    ->columnSpan(1)
                    ->options(function () {
                        return Currency::all()
                            ->mapWithKeys(function ($currency) {
                                return [$currency->id => $currency->name . ' (' . $currency->symbol . ')'];
                            });
                    }),
                SelectFilter::make('created_by')
                    ->label(__('learning/learningTest.created_by'))
                    ->columnSpan(1)
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        return LearningTest::all()
                            ->mapWithKeys(function ($category) {
                                return [$category->createdBy->id => $category->createdBy->name];
                            });
                    }),
                Filter::make('is_free')
                    ->columnSpan(2)
                    ->columns(2)
                    ->form([
                        Select::make('is_free')
                            ->live()
                            ->options([
                                true => __('learning/learningTest.fields.free'),
                                false => __('learning/learningTest.fields.paid'),
                            ])
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state == true) {
                                    $set('price_from', 0);
                                    $set('price_to', 0);
                                }
                            })
                            ->columnSpan(2)
                            ->label(__('learning/learningTest.price'))
                            ->native(false),
                        TextInput::make('price_from')
                            ->label(__('learning/learningTest.fields.price_from'))
                            ->numeric()
                            ->live()
                            ->visible(function ($get) {
                                $isFree = $get('is_free');
                                return $isFree !== null && $isFree == false;
                            })
                            ->columnSpan(1)
                            ->minValue(0),
                        TextInput::make('price_to')
                            ->label(__('learning/learningTest.fields.price_to'))
                            ->numeric()
                            ->live()
                            ->columnSpan(1)
                            ->visible(function ($get) {
                                $isFree = $get('is_free');
                                return $isFree !== null && $isFree == false;
                            })
                            ->minValue(0),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (empty($data) || !isset($data['is_free']) || $data['is_free'] === null) {
                            return null;
                        }

                        if ($data['is_free'] == true) {
                            return __('learning/learningCategory.fields.free');
                        }

                        return __('learning/learningTest.paid_from') . ': ' . $data['price_from'] . ' ' . __('learning/learningTest.paid_to') . ': ' . $data['price_to'];
                    })
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
                                $query->WhereRaw('(price - (price * discount / 100)) >= ?', [$data['price_from']]);
                            }

                            if (!empty($data['price_to'])) {
                                $query->WhereRaw('(price - (price * discount / 100)) <= ?', [$data['price_to']]);
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
                if (Auth::user()->role_id > 2) {
                    return $query->where(function ($mainQuery) {

                        $mainQuery->where(function ($q) {
                            $q->where('is_active', true)
                                ->where('is_public', true)
                                ->where(function ($subQuery) {
                                    $subQuery->where('available_for_everyone', true);

                                    if (Auth::user()->school_id) {
                                        $subQuery->orWhere(function ($schoolQuery) {
                                            $schoolQuery->where('is_public', true)
                                                ->whereHas('createdBy', function ($userQuery) {
                                                    $userQuery->where('school_id', Auth::user()->school_id);
                                                });
                                        });
                                    }
                                });
                        })
                            // user owns tests
                            ->orWhere('created_by', Auth::id());
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
