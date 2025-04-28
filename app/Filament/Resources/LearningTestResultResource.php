<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Filament\Resources\Resource;
use App\Models\LearningTestResult;
use App\Tables\Columns\ToHumanTime;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\LearningTestResultResource\Pages\CreateCustomTestResult;
use App\Filament\Resources\LearningTestResultResource\Pages\EditLearningTestResult;
use App\Filament\Resources\LearningTestResultResource\Pages\LearningTestFinishPage;
use App\Filament\Resources\LearningTestResultResource\Pages\ListLearningTestResults;
use App\Filament\Resources\LearningTestResultResource\Pages\CreateLearningTestResult;
use App\Filament\Resources\LearningTestResultResource\RelationManagers\DetailsRelationManager;

class LearningTestResultResource extends Resource
{
    protected static ?string $model = LearningTestResult::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getLabel(): string
    {
        return __('learning/learningTestResult.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('learning/learningTestResult.label_plural');
    }

    public static function canCreate(): bool
    {
        return false;
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
        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make('Completed test result')
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
                        Select::make('user_id')
                            ->label('User')
                            ->searchable()
                            ->relationship('user', 'name')
                            ->options(User::all()->pluck('name', 'id'))
                            ->columnSpan([
                                'default' => 9,
                                'sm' => 10,
                                'md' => 10,
                                'lg' => 10,
                            ]),
                        Toggle::make('is_passed')
                            ->label('Passed')
                            ->columnSpan([
                                'default' => 3,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                            ])
                            ->inline(false),
                        Select::make('test_id')
                            ->label('Test')
                            ->searchable()
                            ->relationship('test', 'name')
                            ->options(LearningTest::all()->pluck('name', 'id'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('points')
                            ->label('User score')
                            ->live()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->suffix(function (?Model $record) {
                                $maxPoints = $record->test->details->where('is_active', true)->sum('points');
                                $minScore = $record->test->min_score;

                                if ($maxPoints) {
                                    return "Points to pass: $minScore / $maxPoints";
                                }

                                return null;
                            })
                            ->suffixIcon('tabler-award')
                            ->rules(['required', 'integer', 'min:0'])
                            ->afterStateUpdated(function (Model $record, $set, $state) {
                                $set('is_passed', $state >= $record->test->min_score);
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AvatarWithDetails::make('user.name')
                    ->label(__('employee.fields.name'))
                    ->title(function ($record) {
                        return $record->user->name;
                    })
                    ->description(function ($record) {
                        return $record->user->job_title;
                    })
                    ->avatar(function (?Model $record) {
                        return $record->user->avatar;
                    }, function (?Model $record) {
                        return $record?->user->name;
                    })
                    ->avatarType('image')
                    ->searchable()
                    ->sortable(),
                AvatarWithDetails::make('test.name')
                    ->label('Test')
                    ->title(function (Model $record) {
                        return $record->test->name;
                    })
                    ->avatar(function (Model $record) {
                        return $record->test->thumbnail;
                    })
                    ->avatarType('image')
                    ->link(function (Model $record) {
                        return "/learning-tests/$record->test_id/view";
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('finished_at')
                    ->label(__('learning/learningTestResult.custom.finished_at'))
                    ->searchable()
                    ->sortable()
                    ->default('In progress')
                    ->toggleable(isToggledHiddenByDefault: false),
                ToHumanTime::make('total_time')
                    ->label(__('learning/learningTestResult.custom.total_time'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('points')
                    ->label(__('learning/learningTestResult.custom.points'))
                    ->searchable()
                    ->sortable()
                    ->default(0)
                    ->toggleable(isToggledHiddenByDefault: false),
                IconColumn::make('is_passed')
                    ->label(__('learning/learningTestResult.custom.passed'))
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->default(false)
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id < 3) {
                    return $query;
                }

                return $query->where('user_id', Auth::user()->id);
            })
            ->filters([
                TernaryFilter::make('is_passed')
                    ->label(__('learning/learningTestResult.custom.passed')),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->recordUrl(function (Model $record) {
                $incompleteTest = LearningTestResult::where('test_id', $record->test_id)->whereNull('finished_at')->first();

                if (!is_null($incompleteTest)) {
                    return CreateCustomTestResult::getUrl(['record' => $incompleteTest->test_id, 'question' => 1, 'viewTest' => 0], isAbsolute: false);
                }

                return CreateCustomTestResult::getUrl(['record' => $record->id, 'question' => 1, 'viewTest' => 1], isAbsolute: false);
            });
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLearningTestResults::route('/'),
            'create' => CreateLearningTestResult::route('/create'),
            'edit' => EditLearningTestResult::route('/{record}/edit'),

            'do-test' => CreateCustomTestResult::route('/{record}/do-test/{question}/{viewTest}'),
            'finish-page' => LearningTestFinishPage::route('/{record}/finish-page'),
        ];
    }
}
