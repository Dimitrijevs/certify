<?php

namespace App\Filament\Resources\LearningTestResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
                        'group' => 'Group',
                        'student' => 'Student',
                    ])
                    ->afterStateUpdated(function ($set) {
                        $set('entity_id', null);
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
                    ->label('Student')
                    ->relationship('student', 'name', function (Builder $query, $operation) {
                        if ($operation === 'create') {
                            $test_id = $this->getOwnerRecord()->id;
                            $query->whereNotExists(function ($subQuery) use ($test_id) {
                                $subQuery->select(DB::raw(1))
                                    ->from('learning_certification_requirements')
                                    ->whereColumn('learning_certification_requirements.entity_type', DB::raw("'student'"))
                                    ->whereColumn('learning_certification_requirements.entity_id', 'users.id')
                                    ->where('learning_certification_requirements.test_id', $test_id)
                                    ->where('users.role_id', '>', 2);
                            });
                        } else {
                            return $query;
                        }
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
                    ->label('Group')
                    ->relationship('group', 'name', function (Builder $query, $operation) {

                        $query->orderBy('groups.school_id', 'asc');

                        if ($operation === 'create') {
                            $test_id = $this->getOwnerRecord()->id;
                            $query->whereNotExists(function ($subQuery) use ($test_id) {
                                $subQuery->select(DB::raw(1))
                                    ->from('learning_certification_requirements')
                                    ->whereColumn('learning_certification_requirements.entity_type', DB::raw("'group'"))
                                    ->whereColumn('learning_certification_requirements.entity_id', 'groups.id')
                                    ->where('learning_certification_requirements.test_id', $test_id);
                            });

                            return $query;
                        } else {
                            return $query;
                        }
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return $record->name . ' (' . $record->school->name . ')';
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
            ->recordTitleAttribute('entity_type')
            ->columns([
                TextColumn::make('entity_type')
                    ->label(__('learning/learningCertificateRequirement.fields.entity_type'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        $entity_type = $record->entity_type;

                        if ($entity_type === 'group') {
                            return 'Group';
                        } else if ($entity_type === 'student') {
                            return 'Student';
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
                            return $record->student->name;
                        }
                    }),
            ])
            ->filters([
                SelectFilter::make('entity_type')
                    ->options([
                        'group' => 'Group',
                        'student' => 'Student',
                    ])
                    ->searchable()
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->closeModalByClickingAway(false)
                    ->modalHeading(__('learning/learningTestRequirements.form.create') . ' ' . __('learning/learningTestRequirements.label'))
                    ->label(__('learning/learningTestRequirements.form.create')),
            ])
            ->actions([
                EditAction::make()
                    ->closeModalByClickingAway(false),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
