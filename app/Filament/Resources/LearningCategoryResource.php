<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
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
use App\Forms\Components\UserStatWidget;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\CustomImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\LearningCategoryResource\Pages;
use App\Filament\Resources\LearningCategoryResource\Pages\CourseWelocomePage;
use App\Filament\Resources\LearningCategoryResource\Pages\CustomEditResource;
use App\Filament\Resources\LearningCategoryResource\Pages\ViewCustomLearningResource;
use App\Filament\Resources\LearningCategoryResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\LearningCategoryResource\RelationManagers\LearningResourcesRelationManager;

class LearningCategoryResource extends Resource
{
    protected static ?string $model = LearningCategory::class;

    protected static ?string $navigationGroup = 'Learning';

    public static function getLabel(): string
    {
        return __('learning/learningCategory.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('learning/learningCategory.label_plural');
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
        $id = is_null($form->getRecord())
            ? (LearningCategory::latest()->first()->id ?? 0) + 1
            : $form->getRecord()->id;

        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make(__('learning/learningCategory.form.section_title'))
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
                            ->label(__('learning/learningCategory.fields.name'))
                            ->required()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 8,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningCategory.fields.active'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 2,
                            ])
                            ->default(true)
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        Toggle::make('available_for_everyone')
                            ->label(__('learning/learningCategory.fields.available_for_everyone'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 2,
                            ])
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->inline(false),
                        TextInput::make('price')
                            ->label(__('learning/learningCategory.fields.price'))
                            ->live()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('discount')
                            ->label(__('learning/learningCategory.fields.discount'))
                            ->live()
                            ->prefixIcon('tabler-percentage')
                            ->disabled(function ($get) {
                                return $get('price') == 0 || $get('price') == null;
                            })
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Select::make('currency_id')
                            ->label(__('learning/learningCategory.fields.currency'))
                            ->preload()
                            ->live()
                            ->disabled(function ($get) {
                                return $get('price') == 0 || $get('price') == null;
                            })
                            ->searchable()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 3,
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

                                    return $price . ' ' . $symbol . ' - ' . $discount . ' % = ' . ($price - ($price * $discount / 100)) . ' ' . $symbol;
                                }
                            }),
                        Select::make('language_id')
                            ->label(__('learning/learningCategory.fields.language'))
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
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 3,
                            ]),
                        Select::make('categories')
                            ->label(__('learning/learningCategory.fields.categories'))
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
                        FileUpload::make('thumbnail')
                            ->label(__('learning/learningCategory.fields.thumbnail'))
                            ->disk('public')
                            ->directory('learning_category/' . $id)
                            ->image()
                            ->imageResizeTargetWidth('711')
                            ->imageResizeTargetHeight('400')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
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

                Tabs::make()->columnSpanFull()->tabs([
                    Tab::make(__('learning/learningResource.label_plural'))
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(LearningResourcesRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ])->visible(fn(string $operation): bool => $operation !== 'create'),
                    Tab::make(__('learning/learningResourceActivity.label_plural'))
                        ->icon('tabler-user')
                        ->schema([
                            UserStatWidget::make('user_statistics')
                                ->label(' ')
                                ->dehydrated(false)
                                ->columnSpanFull(),
                            RelationManager::make()
                                ->manager(ActivitiesRelationManager::class)
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
                        ->words(15)
                        ->markdown(),
                    TextColumn::make('price')
                        ->searchable()
                        ->formatStateUsing(function ($record) {
                            if ($record->price > 0 && $record->discount > 0) {
                                return $record->price . ' € - ' . $record->discount . ' % = ' . ($record->price - ($record->price * $record->discount / 100)) . ' €';
                            } else if ($record->price > 0 && $record->discount == 0) {
                                return $record->price . ' €';
                            } else {
                                return __('learning/learningCategory.fields.free');
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
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->filters([
                Filter::make('my_courses')
                    ->columns(1)
                    ->columnSpan(2)
                    ->form([
                        Select::make('my_courses')
                            ->label(__('learning/learningCategory.my_courses'))
                            ->options([
                                true => __('other.yes'),
                                false => __('other.no'),
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if (!$data['my_courses']) {
                            return $query;
                        }

                        return $query->where('created_by', Auth::id());
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (empty($data) || $data['my_courses'] === null || $data['my_courses'] == false) {
                            return null;
                        }

                        return __('learning/learningCategory.my_courses');
                    }),
                Filter::make('my_purchased_courses')
                    ->columns(1)
                    ->columnSpan(2)
                    ->form([
                        Select::make('my_purchased_courses')
                            ->label(__('learning/learningCategory.my_purchased_courses'))
                            ->options([
                                true => __('other.yes'),
                                false => __('other.no'),
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if (!$data['my_purchased_courses']) {
                            return $query;
                        }

                        return $query->whereHas('purchases', function (Builder $subQuery) {
                            $subQuery->where('user_id', Auth::id());
                        });
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (empty($data) || $data['my_purchased_courses'] === null || $data['my_purchased_courses'] == false) {
                            return null;
                        }

                        return __('learning/learningCategory.my_purchased_courses');
                    }),
                TernaryFilter::make('is_active')
                    ->label(__('learning/learningCategory.fields.active'))
                    ->columnSpan(2)
                    ->native(false)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                TernaryFilter::make('is_public')
                    ->label(__('learning/learningCategory.fields.public'))
                    ->native(false)
                    ->columnSpan(2)
                    ->visible(function () {
                        return Auth::user()->role_id < 3;
                    }),
                SelectFilter::make('language_id')
                    ->label(__('learning/learningCategory.fields.language'))
                    ->columnSpan(2)
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        return Language::all()
                            ->mapWithKeys(function ($lang) {
                                return [$lang->id => $lang->name . ' (' . $lang->iso2 . ', ' . $lang->iso3 . ')'];
                            });
                    }),
                Filter::make('category')
                    ->columnSpan(2)
                    ->columns(1)
                    ->form([
                        Select::make('category_ids')
                            ->label(__('learning/learningCategory.fields.category'))
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
                    ->columnSpan(4)
                    ->options(function () {
                        return Currency::all()
                            ->mapWithKeys(function ($currency) {
                                return [$currency->id => $currency->name . ' (' . $currency->symbol . ')'];
                            });
                    }),
                Filter::make('is_free')
                    ->columnSpan(4)
                    ->columns(2)
                    ->form([
                        Select::make('is_free')
                            ->label(__('learning/learningCategory.fields.price'))
                            ->live()
                            ->options([
                                true => __('learning/learningCategory.fields.free'),
                                false => __('learning/learningCategory.fields.paid'),
                            ])
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state == true) {
                                    $set('price_from', null);
                                    $set('price_to', null);
                                }
                            })
                            ->columnSpan(2)
                            ->native(false),
                        TextInput::make('price_from')
                            ->label(__('learning/learningCategory.fields.price_from'))
                            ->numeric()
                            ->live()
                            ->visible(function ($get) {
                                $isFree = $get('is_free');
                                return $isFree !== null && $isFree == false;
                            })
                            ->columnSpan(1)
                            ->minValue(0),
                        TextInput::make('price_to')
                            ->label(__('learning/learningCategory.fields.price_to'))
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

                        return __('learning/learningCategory.paid_from') . ': ' . $data['price_from'] . ' ' . __('learning/learningCategory.paid_to') . ' : ' . $data['price_to'];
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
            ->filtersFormColumns(4)
            ->filtersFormWidth(MaxWidth::TwoExtraLarge)
            ->defaultSort('id', 'desc')
            ->actions([
                //
            ])
            ->bulkActions([
                // 
            ])
            ->recordUrl(
                function (Model $record): ?string {
                    return CourseWelocomePage::getUrl([
                        'record' => $record->id,
                    ], isAbsolute: false);
                },
            );
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
            'index' => Pages\ListLearningCategories::route('/'),
            'create' => Pages\CreateLearningCategory::route('/create'),
            'edit' => Pages\EditLearningCategory::route('/{record}/edit'),

            'course-welcome-page' => Pages\CourseWelocomePage::route('/{record}/welcome'),

            // for resource relation mamanger
            'resource' => ViewCustomLearningResource::route('/resource/{record}'),
            'editResource' => CustomEditResource::route('/resource/{record}/edit'),
        ];
    }
}
