<?php

namespace App\Filament\Resources\LearningTestResultResource\RelationManagers;

use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTestAnswer;
use App\Tables\Columns\ToHumanTime;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $model = LearningTestAnswer::class;

    protected function canCreate(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                TextInput::make('user_answer')
                    ->label('User answer')
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->required(),
                TextInput::make('points')
                    ->label('Points')
                    ->required()
                    ->live()
                    ->rules(function (?Model $record) {
                        $totalPoints = $record->question->points;
                        return ['integer', 'max:' . $totalPoints];
                    })
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ])
                    ->suffix(function (?Model $record, $get) {
                        if ($get('points') === null) {
                            $pointsRecieved = 0;
                        } else if ($get('points') > $record->question->points) {
                            $pointsRecieved = $record->question->points;
                        } else if ($get('points') < 0) {
                            $pointsRecieved = 0;
                        } else {
                            $pointsRecieved = $get('points');
                        }

                        $pointsMax = $record->question->points;

                        return "Recieved points: {$pointsRecieved} / {$pointsMax}";
                    })
                    ->suffixIcon('tabler-award'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question.question_title')
                    ->label('Question')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('user_answer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('points')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('question.points')
                    ->label('Max points')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ToHumanTime::make('question_time')
                    ->label('Time')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ActionGroup::make([
                    //
                ])->label('')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('gray')
                    ->button()
                    ->extraAttributes(['style' => 'padding-right: 0.15rem !important; padding-left: 0.425rem !important;']),
                EditAction::make()
                    ->label('')
                    ->tooltip(__('learning/learningTestDetails.table.edit_test'))
                    ->color('gray')
                    ->icon('tabler-arrow-right')
                    ->button()
                    ->after(function (Component $livewire, Model $record) {
                        $livewire->dispatch('refreshTestResult', points: $record->points);
                    })
                    ->extraAttributes(['style' => 'padding-right: 0.2rem !important; padding-left: 0.35rem !important;']),
            ])
            ->bulkActions([
                // 
            ]);
    }
}
