<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use Filament\Resources\Resource;
use App\Models\LearningTestResult;
use App\Models\LearningCertificate;
use App\Tables\Columns\ValidColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Tables\Columns\CustomCertificate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\LearningCertificateResource\Pages\EditLearningCertificate;
use App\Filament\Resources\LearningCertificateResource\Pages\ListLearningCertificates;
use App\Filament\Resources\LearningCertificateResource\Pages\CreateLearningCertificate;
use App\Filament\Resources\LearningCertificateResource\Pages\viewCustomLearningCertificate;

class LearningCertificateResource extends Resource
{
    protected static ?string $model = LearningCertificate::class;

    protected static ?string $navigationGroup = 'Learning';

    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return __('learning/learningCertificate.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('learning/learningCertificate.label_plural');
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
        if ($form->getRecord()) {
            $id = $form->getRecord()->id;
        } else {
            $id = (LearningCertificate::latest()->first()->id ?? 0) + 1;
        }

        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
            ])
            ->schema([
                Section::make(__('learning/learningCertificate.form.section_title'))
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
                            ->label(__('participants.user'))
                            ->live()
                            ->relationship('user', 'name')
                            ->afterStateUpdated(function ($set) {
                                $set('test_id', null);
                                $set('name', null);
                                $set('completed_test_id', null);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        Select::make('test_id')
                            ->label(__('learning/learningCertificate.custom.requirement'))
                            ->live()
                            ->relationship('test', 'name', function ($query) {
                                $query->orderBy('id');
                            })
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $user_id = $get('user_id');

                                $completedTest = LearningTestResult::where('user_id', $user_id)
                                    ->where('test_id', $state)
                                    ->where('is_passed', 1)
                                    ->first();

                                $set('completed_test_id', $completedTest ? $completedTest->id : null);

                                // Fetch the learning test once
                                $learning_test = LearningTest::find($state);

                                if (is_null($learning_test)) {
                                    $set('name', Carbon::now()->year . ' Certificate of Qualification Advancement');
                                } else {
                                    $name = $learning_test->name;
                                    $year = Carbon::now()->year;
                                    $set('name', "$year $name Course");

                                    // Set certificate type
                                    $certificate_type = $learning_test->certificate_type_id;
                                    $set('type_id', $certificate_type);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        DatePicker::make('valid_to')
                            ->label(__('learning/learningCertificate.fields.valid_untill'))
                            ->required()
                            ->after(Carbon::now()->toDateString())
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        TextInput::make('name')
                            ->label(__('learning/learningCertificate.fields.title'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),

                        // if the selected test is completed, then the completed_test_id will be set 
                        Hidden::make('completed_test_id'),

                        FileUpload::make('thumbnail')
                            ->label(__('learning/learningCertificate.fields.certificate'))
                            ->disk('public')
                            ->directory("learning_certificates/$id")
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        RichEditor::make('description')
                            ->label(__('learning/learningCertificate.fields.description'))
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory("learning_certificates/$id")
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                            ->disableToolbarButtons([
                                'attachFiles',
                                'h2',
                                'h3',
                                'codeBlock',
                            ]),
                        Hidden::make('admin_id')
                            ->default(Auth::id() ?? 1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    CustomCertificate::make('thumbnail'),
                    TextColumn::make('name')
                        ->label('Name')
                        ->searchable()
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large),
                    ValidColumn::make('valid_from')
                ]),
            ])
            ->contentGrid([
                'default' => 1,
                'sm' => 2,
                'md' => 2,
                'lg' => 2,
                'xl' => 3,
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role_id != 1) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('user.label'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('test_id')
                    ->label(__('learning/learningTest.label'))
                    ->relationship('test', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])->recordUrl(
                fn(Model $record) => viewCustomLearningCertificate::getUrl(['record' => $record->id], isAbsolute: false)
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
            'index' => ListLearningCertificates::route('/'),
            'create' => CreateLearningCertificate::route('/create'),
            'edit' => EditLearningCertificate::route('/{record}/edit'),

            'viewCertificate' => viewCustomLearningCertificate::route('/{record}/view'),
        ];
    }
}
