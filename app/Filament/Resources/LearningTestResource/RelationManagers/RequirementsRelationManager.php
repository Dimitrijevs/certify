<?php

namespace App\Filament\Resources\LearningTestResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
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
                        'department' => __('learning/learningTestRequirements.options.department'),
                        'employee_team' => __('learning/learningTestRequirements.options.employee_team'),
                        'employee' => __('learning/learningTestRequirements.options.employee'),
                    ])
                    ->afterStateUpdated(function ($set) {
                        $set('entity_id', null);
                    })
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->required(),

                // employee
                Select::make('entity_id')
                    ->live()
                    ->label(__('learning/learningTestRequirements.fields.entity'))
                    ->relationship('employee', 'name', function (Builder $query, $operation) {
                        if ($operation === 'create') {
                            $test_id = $this->getOwnerRecord()->id;
                            $query->whereNotExists(function ($subQuery) use ($test_id) {
                                $subQuery->select(DB::raw(1))
                                    ->from('learning_certification_requirements')
                                    ->whereColumn('learning_certification_requirements.entity_type', DB::raw("'employee'"))
                                    ->whereColumn('learning_certification_requirements.entity_id', 'employees.id')
                                    ->where('learning_certification_requirements.test_id', $test_id);
                            });
                        } else {
                            return $query;
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(function ($get) {
                        return $get('entity_type') !== 'employee';
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

                        if ($entity_type === 'department') {
                            return __('learning/learningTestRequirements.options.department');
                        } else if ($entity_type === 'employee_team') {
                            return __('learning/learningTestRequirements.options.employee_team');
                        } else if ($entity_type === 'employee') {
                            return __('learning/learningTestRequirements.options.employee');
                        }
                    }),
                TextColumn::make('entity_id')
                    ->label(__('learning/learningCertificateRequirement.table.name'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                            return $record->employee->name;
                    }),
            ])
            ->filters([
                SelectFilter::make('entity_type')
                    ->options([
                        'department' => __('learning/learningTestRequirements.options.department'),
                        'employee_team' => __('learning/learningTestRequirements.options.employee_team'),
                        'employee' => __('learning/learningTestRequirements.options.employee'),
                    ])
                    ->searchable()
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading(__('learning/learningTestRequirements.form.create') . ' ' . __('learning/learningTestRequirements.label'))
                    ->label(__('learning/learningTestRequirements.form.create')),
            ])
            ->actions([
                ActionGroup::make([
                    DeleteAction::make()
                        ->color('gray'),
                ])->label('')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('gray')
                    ->button()
                    ->extraAttributes(['style' => 'padding-right: 0.15rem !important; padding-left: 0.425rem !important;']),
                EditAction::make()
                    ->label('')
                    ->tooltip(__('learning/learningTestRequirements.table.edit_requirement'))
                    ->color('gray')
                    ->icon('tabler-arrow-right')
                    ->button()
                    ->extraAttributes(['style' => 'padding-right: 0.2rem !important; padding-left: 0.35rem !important;']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
