<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Http\Request;
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

    public function mount(Request $request)
    {
        $this->seller = User::find($request->seller_id);
        $this->price = $this->record->price ?? 0;
    }

    protected static string $view = 'filament.pages.purchase-page';
}
