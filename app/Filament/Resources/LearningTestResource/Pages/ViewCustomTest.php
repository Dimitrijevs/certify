<?php

namespace App\Filament\Resources\LearningTestResource\Pages;

use App\Models\Category;
use App\Models\LearningTest;
use App\Models\UserPurchase;
use Filament\Actions\Action;
use App\Models\LearningCategory;
use App\Models\LearningTestResult;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LearningTestResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewCustomTest extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningTestResource::class;

    public $purchasesCount = 0;

    public function mount(int|string $record): void
    {
        $this->record = LearningTest::findOrFail($record);

        $this->purchasesCount = UserPurchase::where('test_id', $this->record->id)->count();
    }

    public function getTitle(): string
    {
        return __('learning/learningTest.label');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label(__('learning/learningTest.form.edit'))
                ->color('gray')
                ->icon('tabler-eye-edit')
                ->visible(function () {
                    return LearningTest::canUserEdit($this->record->id);
                })
                ->url(LearningTestResource::getUrl('edit', ['record' => $this->record->id])),
        ];
    }

    public function getCategoryName($id): string
    {
        $name = LearningCategory::findOrFail($id)->name;
        return $name;
    }

    public function getCategoriesNames()
    {
        $categories = $this->record->categories;
        $categoryNames = [];

        if ($categories) {
            foreach ($categories as $categoryId) {
                $category = Category::find($categoryId);
                if ($category) {
                    $categoryNames[] = $category->name;
                }
            }
        }

        return $categoryNames;
    }

    public function getTotalPrice()
    {
        $price = $this->record->price ?? 0;
        $discount = $this->record->discount ?? 0;

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

    public function checkUserPurchase()
    {
        $user = Auth::id();
        $purchased = UserPurchase::where('user_id', $user)
            ->where('test_id', $this->record->id)
            ->exists();

        return $purchased;
    }

    public function cooldownFinished()
    {
        if (is_null($this->record->cooldown)) {
            return true;
        } else {
            $lastAttempt = LearningTestResult::where('user_id', Auth::user()->id)
                ->where('test_id', $this->record->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (is_null($lastAttempt)) {
                return true;
            } else {
                $cooldown = $this->record->cooldown;

                $lastAttemptTime = $lastAttempt->created_at;
                $cooldownTime = $lastAttemptTime->addMinutes($cooldown);
                return now() > $cooldownTime;
            }
        }
    }

    protected static string $view = 'filament.resources.learning-test-resource.pages.view-custom-test';
}
