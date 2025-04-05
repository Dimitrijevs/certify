<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LearningCertificateController;

// landing page
Route::get('/', function () {
    return view('pages.landingpage');
})->name('landingpage');

// user logout
Route::get('/user-logout', function () {
    Auth::logout();

    return redirect()->route('filament.app.auth.login');
})->name('user-logout');    

// certificate pdfs
Route::get('/learning-resources/{learningResource}/pdf', [LearningCertificateController::class, 'index'])
    ->name('learning-resources.pdf');

// stripe
Route::get('stripe/{id}', [SellerController::class, 'redirectToStripe'])
    ->name('redirect.stripe');

Route::get('connect/{token}', [SellerController::class, 'saveStripeAccount'])
    ->name('save.stripe');

// accept invite
Route::get('accept-invite/{institution}/{group}}', [InvitationController::class, 'acceptInvite'])
    ->name('accept-invite');

// reject invite
Route::get('reject-invite/{institution}/{group}', [InvitationController::class, 'rejectInvite'])
    ->name('reject-invite');
