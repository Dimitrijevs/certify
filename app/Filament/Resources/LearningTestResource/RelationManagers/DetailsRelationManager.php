<?php

namespace App\Filament\Resources\LearningTestResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTestDetail;
use App\Tables\Columns\AnswerOptions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use App\Tables\Columns\CustomCorrectAnswer;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('learning/learningTestDetails.label_plural');
    }

    public function getQuestionName($array)
    {
        if ($array) {
            foreach ($array as $item) {
                if ($item['is_correct'] == true) {
                    return $item['answer'];
                }
            }

            return __('learning/learningTestDetails.no_correct_answer');
        } else {
            return __('learning/learningTestDetails.no_answers');
        }
    }

    public function form(Form $form): Form
    {
        $id = $form->getRecord() ? $form->getRecord()->getKey() : LearningTestDetail::latest()->first()->id + 1;
        return $form
            ->columns(12)
            ->schema([
                TextInput::make('question_title')
                    ->label(__('learning/learningTestDetails.fields.name'))
                    ->required()
                    ->columnSpan(9),
                Toggle::make('is_active')
                    ->label(__('learning/learningTestDetails.fields.active'))
                    ->columnSpan(3)
                    ->inline(false),
                FileUpload::make('visual_content')
                    ->label(__('learning/learningTestDetails.fields.image'))
                    ->image()
                    ->disk('public')
                    ->directory('learning_questions/' . $id)
                    ->nullable()
                    ->columnSpan(12),
                RichEditor::make('question_description')
                    ->label(__('learning/learningTestDetails.fields.description'))
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpan(12),
                Select::make('answer_type')
                    ->label(__('learning/learningTestDetails.fields.answer_type'))
                    ->required()
                    ->columnSpan(6)
                    ->live()
                    ->options([
                        'text' => __('learning/learningTestDetails.fields.text'),
                        'select_option' => __('learning/learningTestDetails.fields.one_option'),
                    ])
                    ->afterStateUpdated(function ($set, $get) {
                        if ($get('answer_type') == 'text' && count($get('answers')) > 1) {
                            $set('answers', array_slice($get('answers'), 0, 1));

                            $answers = $get('answers');
                            if (!empty($answers)) {
                                foreach ($answers as &$answer) {
                                    $answer['is_correct'] = true;
                                    break;
                                }
                                $set('answers', $answers);
                            }
                        } else if ($get('answer_type') == 'select_option' && count($get('answers')) == 1) {
                            $answers = $get('answers');
                            if (!empty($answers)) {
                                foreach ($answers as &$answer) {
                                    $answer['is_correct'] = false;
                                    break;
                                }
                                $set('answers', $answers);
                            }
                        } else if ($get('answer_type') == 'text' && count($get('answers')) == 1) {
                            $answers = $get('answers');
                            if (!empty($answers)) {
                                foreach ($answers as &$answer) {
                                    $answer['is_correct'] = true;
                                    break;
                                }
                                $set('answers', $answers);
                            }
                        }
                    }),
                TextInput::make('points')
                    ->label(__('learning/learningTestDetails.fields.points'))
                    ->columnSpan(6)
                    ->placeholder('5')
                    ->rules('integer')
                    ->required()
                    ->default(1)
                    ->maxValue(5),

                // answers
                Repeater::make('answers')
                    ->live()
                    ->relationship('answers')
                    ->columns(12)
                    ->label(function ($get) {
                        return $get('answer_type') == 'text' ? __('learning/learningTestDetails.fields.answer') : __('learning/learningTestDetails.fields.answer_options');
                    })
                    ->schema([
                        TextInput::make('answer')
                            ->label(__('learning/learningTestDetails.fields.answer'))
                            ->required()
                            ->columnSpan(function ($get) {
                                return $get('../../answer_type') == 'select_option' ? 10 : 12;
                            }),
                        Toggle::make('is_correct')
                            ->label(__('learning/learningTestDetails.fields.correct'))
                            ->live()
                            ->columnSpan(2)
                            ->inline(false)
                            ->afterStateUpdated(function ($set, $get) {
                                $answers = $get('../../answers');

                                $totalTrue = 0;
                                foreach ($answers as &$answer) {
                                    if ($answer['is_correct'] == true)
                                        $totalTrue++;
                                }

                                if ($totalTrue > 1) {
                                    // Set all is_correct values to false
                                    foreach ($answers as &$answer) {
                                        $answer['is_correct'] = false;
                                    }

                                    // Set all values to false
                                    $set('../../answers', $answers);

                                    // Set the current answer is_correct value to true
                                    $set('is_correct', true);
                                }
                            })
                            ->visible(function ($get) {
                                return $get('../../answer_type') == 'select_option';
                            }),

                        Hidden::make('is_correct')
                            ->live()
                            ->hidden(false)
                            ->visible(function ($get) {
                                return $get('../../answer_type') == 'text';
                            })
                            ->afterStateUpdated(function ($set, $get) {
                                $answers = $get('../../answers');

                                $totalTrue = 0;
                                foreach ($answers as &$answer) {
                                    if ($answer['is_correct'] == true)
                                        $totalTrue++;
                                }

                                if ($totalTrue > 1) {
                                    // Set all is_correct values to false
                                    foreach ($answers as &$answer) {
                                        $answer['is_correct'] = false;
                                    }

                                    // Set all values to false
                                    $set('../../answers', $answers);

                                    // Set the current answer is_correct value to true
                                    $set('is_correct', true);
                                }
                            }),
                        Hidden::make('order_id')
                            ->required()
                            ->default(function ($get, $set) {
                                $answers = $get('../../answers');
                                if (count($answers) > 0) {
                                    $maxOrderId = 0;
                                    $counter = 1;

                                    foreach ($answers as &$answer) {

                                        if (isset($answer['order_id']) && $answer['order_id'] !== $counter) {
                                            $answer['order_id'] = $counter;
                                        }

                                        if (isset($answer['order_id']) && is_numeric($answer['order_id']) && $answer['order_id'] > $maxOrderId) {
                                            $maxOrderId = $answer['order_id'];
                                        }

                                        $counter++;
                                    }

                                    $set('../../answers', $answers);

                                    return $maxOrderId + 1;
                                } else {
                                    return 1;
                                }
                            }),
                    ])
                    ->maxItems(function ($get) {
                        return $get('answer_type') == 'select_option' ? 4 : 1;
                    })
                    ->minItems(1)
                    ->columnSpan(12)
                    ->hidden(function ($get) {
                        return $get('answer_type') == null;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_title')
                    ->label(__('learning/learningTestDetails.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->words(3),
                TextColumn::make('question_description')
                    ->label(__('learning/learningTestDetails.fields.description'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->words(4),
                ImageColumn::make('visual_content')
                    ->label(__('learning/learningTestDetails.fields.image'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignment('center')
                    ->square(),
                TextColumn::make('answer_type')
                    ->label(__('learning/learningTestDetails.fields.answer_type'))
                    ->badge()
                    ->color(function ($state) {
                        if ($state == "text") {
                            return 'warning';
                        } else if ($state == "select_option") {
                            return 'primary';
                        }
                    })
                    ->formatStateUsing(function ($state) {
                        if ($state == "text") {
                            return __('learning/learningTestDetails.fields.text');
                        } else if ($state == "select_option") {
                            return __('learning/learningTestDetails.fields.one_option');
                        }
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                AnswerOptions::make('answers.answer')
                    ->label(__('learning/learningTestDetails.fields.answer_options')),
                CustomCorrectAnswer::make('answers')
                    ->label(__('learning/learningTestDetails.fields.answer')),
                TextColumn::make('points')
                    ->label(__('learning/learningTestDetails.fields.points'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                ToggleColumn::make('is_active')
                    ->label(__('learning/learningTestDetails.fields.active'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->label(__('learning/learningTestDetails.fields.created_at'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('learning/learningTestDetails.fields.active')),
                TernaryFilter::make('is_visual_content')
                    ->label(__('learning/learningTestDetails.fields.image')),
                TernaryFilter::make('is_crucial_question')
                    ->label(__('learning/learningTestDetails.fields.essential'))
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading(__('learning/learningTest.custom.add_test_details'))
                    ->label(__('learning/learningTestDetails.form.create')),
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
