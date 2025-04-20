<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerQuestion;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Tables\Columns\AvatarWithDetails;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\CustomerQuestionResource\Pages;

class CustomerQuestionResource extends Resource
{
    protected static ?string $model = CustomerQuestion::class;

    protected static ?string $navigationGroup = 'Additional';

    public static function canCreate(): bool
    {
        return true;
    }

    public static function shouldRegisterNavigation(): bool
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
        return $form
            ->columns([
                'default' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12
            ])
            ->schema([
                Section::make('Support Request General Information')
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12
                    ])
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12
                    ])
                    ->schema([
                        Hidden::make('user_id')
                            ->default(Auth::id()),
                            
                        TextInput::make('title')
                            ->label('Title')
                            ->minLength(3)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12
                            ])
                            ->required(),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->minLength(10)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AvatarWithDetails::make('title')
                    ->label('Request')
                    ->title(function ($record) {
                        return $record->title;
                    })
                    ->description(function ($record) {
                        return $record->description;
                    })
                    ->tooltip(function ($record) {
                        return $record->title;
                    })
                    ->icon('tabler-file')
                    ->avatarType('icon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                AvatarWithDetails::make('user.name')
                    ->label('User')
                    ->title(function ($record) {
                        return $record->user->name;
                    })
                    ->description(function ($record) {
                        return $record->user->email;
                    })
                    ->avatar(function ($record) {
                        return $record->user->avatar;
                    })
                    ->tooltip(function ($record) {
                        return $record->user->email;
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->avatarType('image')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_answered')
                    ->label('Answered')
                    ->searchable()
                    ->sortable()
                    ->boolean()
                    ->default(false)
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                TernaryFilter::make('is_answered')
                    ->native(false)
                    ->label('Answered'),
            ])
            ->actions([
                Action::make('mark_as_answered')
                    ->label('Mark as answered')
                    ->color('success')
                    ->icon('tabler-circle-check')
                    ->visible(function ($record) {
                        return !$record->is_answered;
                    })
                    ->action(function ($record) {
                        $record->update([
                            'is_answered' => true,
                            'answered_by' => Auth::id()
                        ]);

                        Notification::make()
                            ->title('Request succesfully updated!')
                            ->success()
                            ->send();
                    }),
                Action::make('mark_as_unanswered')
                    ->label('Mark as unanswered')
                    ->color('danger')
                    ->icon('tabler-circle-x')
                    ->visible(function ($record) {
                        return $record->is_answered;
                    })
                    ->action(function ($record) {
                        $record->update([
                            'is_answered' => false,
                            'answered_by' => null
                        ]);

                        Notification::make()
                            ->title('Request succesfully updated!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCustomerQuestions::route('/'),
            'create' => Pages\CreateCustomerQuestion::route('/create'),
            'edit' => Pages\EditCustomerQuestion::route('/{record}/edit'),
        ];
    }
}
