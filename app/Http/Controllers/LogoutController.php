<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Http\Controllers\Auth\LogoutController as FilamentLogoutController;

class LogoutController extends FilamentLogoutController
{
    public function logout(Request $request)
    {
        $locale = $request->cookie('filament_language_switch_locale');

        Filament::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app()->setLocale($locale);

        Notification::make()
            ->title(__('other.logged_out_successfully'))
            ->success()
            ->send();

        return redirect()->route('landingpage');
    }
}