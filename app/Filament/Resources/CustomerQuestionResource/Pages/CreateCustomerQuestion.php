<?php

namespace App\Filament\Resources\CustomerQuestionResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\CustomerQuestionResource;

class CreateCustomerQuestion extends CreateRecord
{
    protected static string $resource = CustomerQuestionResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('question.add_customer_request');
    }

    protected function getRedirectUrl(): string
    {
        if (Auth::user()->role_id < 3) {
            return route('filament.app.resources.customer-questions.index');
        }

        return route('filament.app.pages.dashboard');
    }
}
