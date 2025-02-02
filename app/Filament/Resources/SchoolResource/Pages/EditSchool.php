<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SchoolResource;
use Illuminate\Contracts\Support\Htmlable;

class EditSchool extends EditRecord
{
    protected static string $resource = SchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ViewAction::make()
                ->url('view'),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return __('other.edit_record');
    }
}
