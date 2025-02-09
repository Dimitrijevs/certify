<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearningCertificateController;

Route::get('/', function () {
    return view('pages.landingpage');
})->name('landingpage');

Route::get('/user-logout', function () {
    Auth::logout();

    return redirect()->route('filament.app.auth.login');
})->name('user-logout');    

// certificate pdfs
Route::get('/learning-resources/{learningResource}/pdf', [LearningCertificateController::class, 'index'])->name('learning-resources.pdf');
