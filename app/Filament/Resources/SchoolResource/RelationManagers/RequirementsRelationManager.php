<?php

namespace App\Filament\Resources\SchoolResource\RelationManagers;

use App\Models\User;
use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
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
                Select::make('test_id')
                    ->live()
                    ->label(__('learning/learningTestRequirements.test'))
                    ->options(function () {
                        return LearningTest::where('is_active', true)
                            ->where('is_public', true)
                            ->where(function ($subQuery) {
                                $subQuery->where('created_by', $this->getOwnerRecord()->created_by)
                                    ->orWhere('available_for_everyone', true);
                            })
                            ->get()
                            ->mapWithKeys(function ($record) {
                                $discount = $record->price / 100 * $record->discount;

                                $price = $record->price - $discount;
                                $price = $price > 0 ? $price : 0;

                                return [$record->id => $record->name . ' (' . $price . ' ' . $record->currency?->symbol . ')'];
                            });
                    })
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    ->options(function ($operation, $get) {
                        $not_include = LearningCertificationRequirement::where('test_id', $get('test_id'))
                            ->where('entity_type', 'student')
                            ->pluck('entity_id')
                            ->toArray();

                        $query = User::whereNotIn('id', $not_include)
                            ->where('role_id', '>', 2);

                        if (Auth::user()->role_id > 2 && Auth::id() != $this->getOwnerRecord()->created_by) {
                            if (!Auth::user()->school_id) {
                                return [];
                            } else {
                                $query->where('school_id', $this->getOwnerRecord()->id);
                            }
                        } else {
                            $query->where('school_id', $this->getOwnerRecord()->id);
                        }

                        $students = $query->get();

                        // Then sort by school name, then student name
                        $students = $students->sortBy('name');

                        return $students->mapWithKeys(function ($record) {
                            return [$record->id => $record->name];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(function ($get) {
                        return $get('entity_type') !== 'student';
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
                    ->options(function ($operation, $get) {
                        $not_include = LearningCertificationRequirement::where('test_id', $get('test_id'))
                            ->where('entity_type', 'group')
                            ->where('school_id', $this->getOwnerRecord()->id)
                            ->pluck('entity_id')
                            ->toArray();

                        $query = Group::whereNotIn('id', $not_include);

                        if (Auth::user()->role_id > 2 && Auth::id() != $this->getOwnerRecord()->created_by) {
                            if (!Auth::user()->school_id) {
                                return [];
                            } else {
                                $query->where('school_id', $this->getOwnerRecord()->id);
                            }
                        } else {
                            $query->where('school_id', $this->getOwnerRecord()->id);
                        }

                        // First get results
                        $groups = $query->get();

                        // Then sort by school name, then group name
                        $groups = $groups->sortBy('name');

                        return $groups->mapWithKeys(function ($record) {
                            return [$record->id => $record->name];
                        });
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultGroup('group.name')
            ->columns([
                AvatarWithDetails::make('test.name')
                    ->label(__('learning/learningTestRequirements.test'))
                    ->searchable()
                    ->sortable()
                    ->title(function ($record) {
                        $discount = $record->test->price / 100 * $record->test->discount;

                        $price = $record->test->price - $discount;
                        $price = $price > 0 ? $price : 0;

                        return $record->test->name . ' (' . $price . ' ' . $record->test->currency?->symbol . ')';
                    })
                    ->description(function ($record) {
                        return $record->test->description;
                    })
                    ->tooltip(function ($record) {
                        $discount = $record->test->price / 100 * $record->test->discount;

                        $price = $record->test->price - $discount;
                        $price = $price > 0 ? $price : 0;

                        return $record->test->name . ' (' . $price . ' ' . $record->test->currency?->symbol . ')';
                    })
                    ->avatarType('image')
                    ->avatar(function ($record) {
                        return $record->test->thumbnail;
                    }),
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
                    ->limit(32)
                    ->tooltip(function ($record) {
                        if ($record->entity_type === 'group') {
                            return $record->group->name . ' (' . $record->group->school->name . ')';
                        } else if ($record->entity_type === 'student') {
                            return $record->student->name . ' (' . $record->student->school?->name . ')';
                        }
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->entity_type === 'group') {
                            return $record->group->name . ' (' . $record->group->school->name . ')';
                        } else if ($record->entity_type === 'student') {
                            return $record->student->name . ' (' . $record->student->school?->name . ')';
                        }
                    }),
            ])
            ->filters([
                SelectFilter::make('entity_type')
                    ->label(__('learning/learningTestRequirements.fields.entity_type'))
                    ->options([
                        'group' => __('learning/learningTestRequirements.group'),
                        'student' => __('learning/learningTestRequirements.student'),
                    ])
                    ->native(false)
                    ->preload(),
                SelectFilter::make('test_id')
                    ->label(__('learning/learningTestRequirements.test'))
                    ->options(function () {
                        return LearningTest::where('is_active', true)
                            ->where('is_public', true)
                            ->where(function ($subQuery) {
                                $subQuery->where('created_by', $this->getOwnerRecord()->created_by)
                                    ->orWhere('available_for_everyone', true);
                            })
                            ->get()
                            ->mapWithKeys(function ($record) {
                                $discount = $record->price / 100 * $record->discount;

                                $price = $record->price - $discount;
                                $price = $price > 0 ? $price : 0;

                                return [$record->id => $record->name . ' (' . $price . ' ' . $record->currency?->symbol . ')'];
                            });
                    })
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
