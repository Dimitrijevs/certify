<?php

namespace App\Filament\Resources\LearningCategoryResource\Pages;

use App\Models\Category;
use App\Models\UserPurchase;
use Filament\Actions\Action;
use App\Models\LearningResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\LearningCategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CourseWelocomePage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LearningCategoryResource::class;

    public $purchasesCount = 0;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->purchasesCount = UserPurchase::where('course_id', $this->record->id)->count();
    }

    protected function getCategories()
    {
        $names = [];

        $categoryIds = $this->record->categories ?? [];

        foreach ($categoryIds as $categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $names[] = $category->name;
            }
        }

        return $names;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label(__('other.edit'))
                ->url(route('filament.app.resources.learning-categories.edit', ['record' => $this->record->id]))
                ->icon('tabler-edit')
                ->color('primary')
                ->visible(function () {
                    return Auth::user()->role_id < 3 || Auth::user()->id == $this->record->created_by;
                }),
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    public function availableResources()
    {
        $resources = LearningResource::where('category_id', $this->record->id)
            ->where('is_active', true)
            ->get(['id', 'name']);

        return $resources;
    }

    public function checkUserPurchase()
    {
        $user = Auth::id();
        $is_purchased = UserPurchase::where('user_id', $user)
            ->where('course_id', $this->record->id)
            ->exists();

        return $is_purchased;
    }

    public function getFirstResourceId()
    {
        $firstResourceId = LearningResource::where('category_id', $this->record->id)
            ->where('is_active', true)
            ->value('id');

        return $firstResourceId;
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

    protected static string $view = 'filament.resources.learning-category-resource.pages.course-welocome-page';
}
