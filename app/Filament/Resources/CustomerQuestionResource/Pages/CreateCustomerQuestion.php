<?php

namespace App\Filament\Resources\CustomerQuestionResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerQuestionResource;

class CreateCustomerQuestion extends CreateRecord
{
    protected static string $resource = CustomerQuestionResource::class;

    protected function getRedirectUrl(): string
    {
        if (Auth::user()->role_id < 3) {
            return route('filament.app.resources.customer-questions.index');
        }

        return route('filament.app.pages.dashboard');
    }
}
