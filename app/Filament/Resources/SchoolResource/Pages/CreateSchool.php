<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('other.create_new_record');
    }
}
