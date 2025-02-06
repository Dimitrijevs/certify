<?php

namespace App\Filament\Pages\Auth;

use App\Models\Group;
use App\Models\School;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(),
                        $this->getClassFormComponent(),
                        $this->getGroupFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role_id')
            ->label('Role')
            ->options([
                '3' => __('participants.instructor'),
                '4' => __('participants.student'),
            ])
            ->required()
            ->native(false)
            ->preload();
    }

    protected function getGroupFormComponent(): Component
    {
        return Select::make('group_id')
            ->label('Group')
            ->options(function ($get) {
                if ($get('school_id')) {
                    return Group::where('school_id', $get('school_id'))->pluck('name', 'id');
                }
            })
            ->required()
            ->native(false)
            ->preload();
    }

    protected function getClassFormComponent(): Component
    {
        return Select::make('school_id')
            ->live()
            ->label('School')
            ->required()
            ->options(School::pluck('name', 'id')->toArray())
            ->native(false)
            ->preload();
    }
}