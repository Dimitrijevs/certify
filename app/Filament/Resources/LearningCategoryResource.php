<?php

namespace App\Filament\Resources;

use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningCategory;
use App\Models\LearningResource;
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
use App\Models\LearningUserStudyRecord;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\CustomImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\LearningCategoryResource\Pages;
use App\Filament\Resources\LearningCategoryResource\Pages\CustomEditResource;
use App\Filament\Resources\LearningCategoryResource\Pages\EditLearningCategory;
use App\Filament\Resources\LearningCategoryResource\Pages\ListLearningCategories;
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
                                'default' => 8,
                                'sm' => 9,
                                'md' => 9,
                                'lg' => 10,
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
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
                            ->visible(function ($get) {
                                return $get('price') > 0;
                            })
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
            ->columns([
                Stack::make([
                    CustomImageColumn::make('thumbnail'),
                    TextColumn::make('name')
                        ->label('Name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),
                    TextColumn::make('description')
                        ->words(15)
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
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id > 2) {
                    return $query->where('is_active', true);
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
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id > 2) {
                    return $query->where('is_active', true)
                        ->where('is_public', true);
                }

                return $query;
            })
            ->actions([
                //
            ])
            ->bulkActions([
                // 
            ])
            ->recordUrl(
                function (Model $record): ?string {
                    $resources = LearningResource::where('category_id', $record->id)->where('is_active', true)->get(['id', 'name']);

                    $activities = [];

                    foreach ($resources as $resource) {
                        $is_seen = LearningUserStudyRecord::where('user_id', Auth::id())
                            ->where('resource_id', $resource->id)
                            ->exists();

                        $activity = new \stdClass();
                        $activity->id = $resource->id;
                        $activity->name = $resource->name;
                        $activity->is_seen = $is_seen;

                        $activities[] = $activity;
                    }

                    if (count($activities) == 0) {

                        if (Auth::user()->role_id !== 3) {
                            return EditLearningCategory::getUrl([
                                'record' => $record->id,
                            ], isAbsolute: false);
                        } else {
                            return ListLearningCategories::getUrl(isAbsolute: false);
                        }
                    } else {
                        foreach ($activities as $activity) {
                            if ($activity->is_seen == false) {
                                return ViewCustomLearningResource::getUrl([
                                    'record' => $activity->id,
                                ], isAbsolute: false);
                            }
                        }

                        return ViewCustomLearningResource::getUrl([
                            'record' => $activities[0]->id,
                        ], isAbsolute: false);
                    }
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

            // for resource relation mamanger
            'resource' => ViewCustomLearningResource::route('/resource/{record}'),
            'editResource' => CustomEditResource::route('/resource/{record}/edit'),
        ];
    }
}
