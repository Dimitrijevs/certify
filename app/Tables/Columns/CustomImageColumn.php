<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class CustomImageColumn extends Column
{
    public $lang;

    public function languageName($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLanguageName(): string
    {
        return $this->evaluate($this->lang);
    }

    protected string $view = 'tables.columns.custom-image-column';
}
