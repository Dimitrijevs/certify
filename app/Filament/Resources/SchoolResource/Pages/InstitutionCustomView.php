<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Models\School;
use Illuminate\Http\Request;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\SchoolResource;

class InstitutionCustomView extends Page
{
    protected static string $resource = SchoolResource::class;

    public $record;

    public function mount(School $record) {
        $this->record = $record;
    }

    protected static string $view = 'filament.resources.school-resource.pages.institution-custom-view';
}
