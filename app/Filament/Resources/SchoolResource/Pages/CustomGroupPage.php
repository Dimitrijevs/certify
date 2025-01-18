<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Models\User;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Resources\SchoolResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Njxqlus\Filament\Components\Forms\RelationManager;
use App\Filament\Resources\SchoolResource\RelationManagers\StudentsRelationManager;

class CustomGroupPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SchoolResource::class;

    protected static string $view = 'filament.resources.school-resource.pages.custom-group-page';

    public ?array $data = [];

    public $record;

    public function mount(Group $record = null): void
    {
        $this->record = $record;

        $data = $record->toArray();

        $this->form->fill($data);
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
                Section::make('Group Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ])
                            ->required(),
                        Select::make('teacher_id')
                            ->label('Teacher')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->options(User::where('role_id', 3)->get()->pluck('name', 'id'))
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                            ]),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->columnSpan([
                                'default' => 12,
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ])
                    ])
                    ->columns([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ])
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                Tabs::make()->columnSpanFull()->tabs([
                    Tab::make('Students')
                        ->icon('tabler-notebook')
                        ->schema([
                            RelationManager::make()
                                ->manager(StudentsRelationManager::class)
                                ->lazy()
                                ->columnSpanFull()
                        ]),
                ])->visible(fn(string $operation): bool => $operation !== 'create'),

            ])
            ->model($this->record)
            ->statePath('data');
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function save()
    {
        $data = $this->form->getState();

        dd($data);

        $this->record->update($data);

        return Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }
}
