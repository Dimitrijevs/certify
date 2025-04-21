<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LearningTest;
use App\Models\UserPurchase;
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
use Filament\Notifications\Notification;
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
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        Select::make('test_id')
                            ->label('Test')
                            ->live()
                            ->options(function ($get) {
                                if ($get('user_id')) {
                                    $userPurchases = UserPurchase::where('user_id', $get('user_id'))
                                        ->whereNotNull('test_id')
                                        ->pluck('test_id')
                                        ->toArray();

                                    return LearningTest::whereIn('id', $userPurchases)->pluck('name', 'id');
                                }

                                return null;
                            })
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $learning_test = LearningTest::find($state);

                                $name = $learning_test->name;
                                $year = Carbon::now()->year;
                                $set('name', "$year $name Course");
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
                        Select::make('completed_test_id')
                            ->live()
                            ->label('Completed Test')
                            ->required()
                            ->native(false)
                            ->preload()
                            ->options(function ($get) {
                                if ($get('user_id') && $get('test_id')) {
                                    return LearningTestResult::where('user_id', $get('user_id'))
                                        ->where('is_passed', true)
                                        ->where('test_id', $get('test_id'))
                                        ->with('test')
                                        ->get()
                                        ->mapWithKeys(function ($item) {
                                            return [$item->id => $item->test->name . ' (' . $item->created_at->format('H:i d M Y.') . ')'];
                                        });
                                }

                                return null;
                            })
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
                        DatePicker::make('valid_to')
                            ->label(__('learning/learningCertificate.fields.valid_untill'))
                            ->required()
                            ->after(Carbon::now()->toDateString())
                            ->default(Carbon::now()->addYears(2)->toDateString())
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
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
            ->defaultSort('created_at', 'desc')
            ->paginated([6, 18, 30, 60, 99])
            ->defaultPaginationPageOption(30)
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
                if (Auth::user()->role_id < 3) {
                    return $query;
                }

                return $query->where('user_id', Auth::user()->id);
            })
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('user.label'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->visible(function () {
                        return Auth::user()->role_id < 4;
                    })
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
                // ...
            ])
            ->bulkActions([
                // ...
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
