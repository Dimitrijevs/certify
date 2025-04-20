<?php

namespace App\Tables\Columns;

use App\Models\Category;
use Filament\Tables\Columns\Column;

class CustomImageColumn extends Column
{
    public $lang;

    public $categories;

    public function languageName($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function categories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    public function getLanguageName(): string|null
    {
        return $this->evaluate($this->lang);
    }

    public function getCategories(): string|null
    {
        $categories = $this->evaluate($this->categories);

        if ($categories) {
            $names = [];

            foreach ($categories as $category) {
                $names[] = Category::find($category)->name;
            }

            return implode(', ', $names);
        }

        return null;
    }

    protected string $view = 'tables.columns.custom-image-column';
}
