<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\CustomImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\LearningCategoryResource\Pages;

class LearningCategoryResource extends Resource
{
    protected static ?string $model = LearningCategory::class;
    protected static ?string $navigationGroup = 'Learning';


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
                        TextInput::make('name')
                            ->label(__('learning/learningCategory.fields.name'))
                            ->required()
                            ->columnSpan(12),
                        FileUpload::make('thumbnail')
                            ->label(__('learning/learningCategory.fields.thumbnail'))
                            ->disk('public')
                            ->directory('learning_category/' . $id)
                            ->image()
                            ->imageResizeTargetWidth('711')
                            ->imageResizeTargetHeight('400')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->columnSpan(12),
                        Textarea::make('description')
                            ->label(__('learning/learningCategory.fields.description'))
                            ->nullable()
                            ->rows(4)
                            ->columnSpan(12),
                        DatePicker::make('active_from')
                            ->label(__('learning/learningCategory.fields.active_from'))
                            ->nullable()
                            ->disabled(function ($get, Set $set) {
                                if ($get('is_active') == 0) {
                                    $set('active_from', null);
                                }
                                return !$get('is_active');
                            })
                            ->rules(['after_or_equal:today'])
                            ->dehydrated()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 5,
                                'lg' => 5,
                            ]),
                        DatePicker::make('active_till')
                            ->label(__('learning/learningCategory.fields.active_to'))
                            ->nullable()
                            ->disabled(function ($get, Set $set) {
                                if ($get('is_active') == 0) {
                                    $set('active_till', null);
                                }
                                return !$get('is_active');
                            })
                            ->afterOrEqual(function ($get) {
                                return $get('active_from');
                            })
                            ->dehydrated()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 5,
                                'lg' => 5,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningCategory.fields.active'))
                            ->live()
                            ->inline(False)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 2,
                                'lg' => 2,
                            ]),
                    ]),

                // Tabs::make()->columnSpanFull()->tabs([
                //     Tab::make(__('learning/learningResource.label_plural'))
                //         ->icon('tabler-notebook')
                //         ->schema([
                //             RelationManager::make()
                //                 ->manager(LearningResourcesRelationManager::class)
                //                 ->lazy()
                //                 ->columnSpanFull()
                //         ])->visible(fn(string $operation): bool => $operation !== 'create'),

                //     Tab::make(__('learning/learningResourceActivity.label_plural'))
                //         ->icon('tabler-user')
                //         ->schema([
                //             RelationManager::make()
                //                 ->manager(ActivitiesRelationManager::class)
                //                 ->lazy()
                //                 ->columnSpanFull()
                //         ]),
                // ])->visible(fn(string $operation): bool => $operation !== 'create'),
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
                        ->markdown()
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (!is_null(Auth::user()->role_id)) {
                    if (Auth::user()->role_id === 3) {
                        return $query->where('is_active', true);
                    }

                    return $query;
                }

                return $query->where('is_active', true);
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
                    ->hidden(function () {
                        if (!is_null(Auth::user()->role_id)) {
                            return Auth::user()->role_id === 3 ? true : false;
                        }

                        return true;
                    }),
            ])
            ->actions([
                // ViewAction::make(),
                // EditAction::make(),
                // DeleteAction::make(),
            ])
            ->bulkActions([
                // 
            ]);
            // ->recordUrl(
            //     function (Model $record): ?string {
            //         $resources = LearningResource::where('category_id', $record->id)->where('is_active', true)->get(['id', 'name']);

            //         $activities = [];

            //         foreach ($resources as $resource) {
            //             $is_seen = LearningUserStudyRecord::where('user_id', Auth::id())
            //                 ->where('resource_id', $resource->id)
            //                 ->exists();

            //             $activity = new \stdClass();
            //             $activity->id = $resource->id;
            //             $activity->name = $resource->name;
            //             $activity->is_seen = $is_seen;

            //             $activities[] = $activity;
            //         }

            //         if (count($activities) == 0) {

            //             if (Auth::user()->role_id !== 3) {
            //                 return EditLearningCategory::getUrl([
            //                     'record' => $record->id,
            //                 ], isAbsolute: false);
            //             } else {
            //                 return ListLearningCategories::getUrl(isAbsolute: false);
            //             }
            //         } else {
            //             foreach ($activities as $activity) {
            //                 if ($activity->is_seen == false) {
            //                     return ViewCustomLearningResource::getUrl([
            //                         'record' => $activity->id,
            //                     ], isAbsolute: false);
            //                 }
            //             }

            //             return ViewCustomLearningResource::getUrl([
            //                 'record' => $activities[0]->id,
            //             ], isAbsolute: false);
            //         }
            //     },
            // );
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
        ];
    }
}
