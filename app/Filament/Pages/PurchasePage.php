<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use App\Models\LearningTest;
use Illuminate\Http\Request;
use App\Models\LearningCategory;

class PurchasePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getSlug(): string
    {
        return 'purchase-page/{type?}/{product_id?}';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $seller;

    public $price;

    public $course;

    public $test;

    public $lang;

    public function mount(Request $request)
    {
        if ($request->type == 'course') {
            $this->course = LearningCategory::find($request->product_id);

            $this->price = $this->getTotalPrice($this->course->price, $this->course->discount);

            $this->seller = User::find($this->course->created_by);
        } else {
            $this->test = LearningTest::find($request->product_id);

            $this->price = $this->getTotalPrice($this->test->price, $this->test->discount);

            $this->seller = User::find($this->test->created_by);
        }

        $this->lang = app()->getLocale();
    }

    public function getTotalPrice($price = 0, $discount = 0): float
    {
        if ($price == 0 || $discount == 100) {
            return 0;
        }

        // Calculate discounted price
        if ($discount > 0) {
            $discountedPrice = $price - ($price * $discount / 100);

            return round($discountedPrice, 2);
        }

        return round($price, 2);
    }

    protected static string $view = 'filament.pages.purchase-page';
}
