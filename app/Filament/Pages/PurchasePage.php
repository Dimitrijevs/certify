<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use App\Models\LearningTest;
use Illuminate\Http\Request;
use App\Models\LearningCategory;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class PurchasePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $seller;

    public $price;

    public $course;

    public $test;

    public function mount(Request $request)
    {
        $this->seller = User::find($request->seller_id);
        $this->price = $request->price;

        if ($request->course_id) {
            $this->course = LearningCategory::find($request->course_id);
        } else {
            $this->test = LearningTest::find($request->test_id);
        }
    }

    protected static string $view = 'filament.pages.purchase-page';
}
