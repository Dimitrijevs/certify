<?php

namespace App\Filament\Resources\LearningTestResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use App\Models\School;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\LearningCertificationRequirement;
use Filament\Resources\RelationManagers\RelationManager;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('learning/learningTestRequirements.label_plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('entity_type')
                    ->label(__('learning/learningTestRequirements.fields.entity_type'))
                    ->live()
                    ->options([
                        'group' => __('learning/learningTestRequirements.group'),
                        'student' => __('learning/learningTestRequirements.student'),
                    ])
                    ->afterStateUpdated(function ($set) {
                        $set('entity_id', null);
                        $set('school_id', null);
                    })
                    ->searchable()
                    ->preload()
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->required(),

                // student
                Select::make('entity_id')
                    ->live()
                    ->label(__('learning/learningTestRequirements.student'))
                    ->options(function ($operation) {
                        $not_include = LearningCertificationRequirement::where('test_id', $this->getOwnerRecord()->id)
                            ->where('entity_type', 'student')
                            ->pluck('entity_id')
                            ->toArray();

                        $query = User::whereNotIn('id', $not_include)
                            ->where('role_id', '>', 2);

                        if (Auth::user()->role_id > 2 && Auth::id() != $this->getOwnerRecord()->created_by) {
                            if (!Auth::user()->school_id) {
                                return [];
                            } else {
                                $query->where('school_id', Auth::user()->school_id);
                            }
                        } else {
                            $query->whereNotNull('school_id');
                        }

                        $students = $query->with('school')->get();

                        // Then sort by school name, then student name
                        $students = $students->sortBy([
                            ['school.name', 'asc'],
                            ['name', 'asc']
                        ]);

                        return $students->mapWithKeys(function ($record) {
                            return [$record->id => $record->name . ' (' . $record->school?->name . ')'];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(function ($get) {
                        return $get('entity_type') !== 'student';
                    })
                    ->afterStateUpdated(function ($set, $state) {
                        $set('school_id', $state);
                    })
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                // group
                Select::make('entity_id')
                    ->live()
                    ->label(__('learning/learningTestRequirements.group'))
                    ->options(function ($operation) {
                        $not_include = LearningCertificationRequirement::where('test_id', $this->getOwnerRecord()->id)
                            ->where('entity_type', 'group')
                            ->pluck('entity_id')
                            ->toArray();

                        $query = Group::whereNotIn('id', $not_include);

                        if (Auth::user()->role_id > 2 && Auth::id() != $this->getOwnerRecord()->created_by) {
                            if (!Auth::user()->school_id) {
                                return [];
                            } else {
                                $query->where('school_id', Auth::user()->school_id);
                            }
                        }

                        // First get results
                        $groups = $query->with('school')->get();

                        // Then sort by school name, then group name
                        $groups = $groups->sortBy([
                            ['school.name', 'asc'],
                            ['name', 'asc']
                        ]);

                        return $groups->mapWithKeys(function ($record) {
                            return [$record->id => $record->name . ' (' . $record->school->name . ')'];
                        });
                    })
                    ->afterStateUpdated(function ($set, $state) {
                        $set('school_id', $state);
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(function ($get) {
                        return $get('entity_type') !== 'group';
                    })
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                Hidden::make('school_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entity_type')
                    ->label(__('learning/learningCertificateRequirement.fields.entity_type'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $entity_type = $record->entity_type;

                        if ($entity_type == 'group') {
                            return __('learning/learningTestRequirements.group');
                        } else if ($entity_type == 'student') {
                            return __('learning/learningTestRequirements.student');
                        }
                    }),
                TextColumn::make('entity_id')
                    ->label(__('learning/learningCertificateRequirement.table.name'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        if ($record->entity_type === 'group') {
                            return $record->group->name . ' (' . $record->group->school->name . ')';
                        } else if ($record->entity_type === 'student') {
                            return $record->student->name . ' (' . $record->student->school?->name . ')';
                        }
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id > 2) {
                    $query
                        ->where('test_id', $this->getOwnerRecord()->id);

                    return $query;
                }

                return $query;
            })
            ->filters([
                SelectFilter::make('entity_type')
                    ->label(__('learning/learningTestRequirements.fields.entity_type'))
                    ->options([
                        'group' => __('learning/learningTestRequirements.group'),
                        'student' => __('learning/learningTestRequirements.student'),
                    ])
                    ->native(false)
                    ->preload(),
                SelectFilter::make('school_id')
                    ->label(__('learning/learningTestRequirements.institution'))
                    ->options(function () {
                        return School::all()
                            ->pluck('name', 'id');
                    })
                    ->visible(fn() => Auth::user()->role_id < 3)
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->closeModalByClickingAway(false)
                    ->modalHeading(__('learning/learningTestRequirements.form.create') . ' ' . __('learning/learningTestRequirements.label'))
                    ->label(__('learning/learningTestRequirements.form.create')),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
