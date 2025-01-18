<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
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

    public static function canCreate(): bool
    {
        return Auth::user()->role_id !== 3;
    }

    public static function getLabel(): string
    {
        return __('learning/learningTest.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('learning/learningTest.label_plural');
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
                        TextInput::make('name')
                            ->label(__('learning/learningTest.fields.name'))
                            ->required()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 6,
                            ]),
                        Select::make('requirement_type')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state == 'certificate') {
                                    $set('min_score', 0);
                                    $set('time_limit', null);
                                }
                            })
                            ->tooltip(__('learning/learningTest.custom.type_of_requirement'))
                            ->label(__('learning/learningTest.fields.requirement_type'))
                            ->options([
                                'test' => __('learning/learningTest.requirements.test'),
                                'certificate' => __('learning/learningTest.requirements.certificate'),
                            ])
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 3,
                            ]),
                        Toggle::make('is_active')
                            ->label(__('learning/learningTest.fields.active'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 1,
                            ])
                            ->inline(false),
                        Toggle::make('is_question_transition_enabled')
                            ->label(__('learning/learningTest.fields.free_navigation'))
                            ->columnSpan([
                                'default' => 6,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 2,
                            ])
                            ->inline(false)
                            ->hidden(function ($get) {
                                return $get('requirement_type') === 'certificate';
                            }),
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
                            ->suffixIcon('tabler-award')
                            ->hidden(function ($get) {
                                return $get('requirement_type') === 'certificate';
                            }),
                        TextInput::make('time_limit')
                            ->label(__('learning/learningTest.fields.time_limit'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->placeholder('30')
                            ->rules('integer')
                            ->hidden(function ($get) {
                                return $get('requirement_type') === 'certificate';
                            }),
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
                                    ->rules(['integer', 'min:0'])
                                    ->hidden(function ($get) {
                                        return $get('requirement_type') === 'certificate';
                                    }),
                            ]),
                        FileUpload::make('thumbnail')
                            ->label(__('learning/learningTest.fields.thumbnail'))
                            ->image()
                            ->rules('image')
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
                        RichEditor::make('description')
                            ->label(__('learning/learningTest.fields.description'))
                            ->columnSpan(12)
                            ->disableToolbarButtons([
                                'attachFiles',
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
                ])->visible(fn(string $operation): bool => $operation !== 'create'),
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
                        if (Auth::user()->role_id) {
                            return Auth::user()->role_id === 3 ? true : false;
                        }

                        return true;
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])->recordUrl(
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
