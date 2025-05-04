<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LearningCertificateController;

// landing page
Route::get('/', function () {
    return view('pages.landingpage');
})->name('landingpage');

Route::post('/app/logout', [LogoutController::class, 'logout'])->name('filament.app.auth.logout');

// certificate pdfs
Route::get('/learning-resources/{learningResource}/pdf', [LearningCertificateController::class, 'index'])
    ->name('learning-resources.pdf');

// stripe, id = seller id
Route::get('stripe/{id}', [SellerController::class, 'redirectToStripe'])
    ->name('redirect.stripe');

Route::get('connect/{token}', [SellerController::class, 'saveStripeAccount'])
    ->name('save.stripe');

Route::post('purchase/{id}', [SellerController::class, 'purchase'])
    ->name('complete.purchase');

// accept invite
Route::get('accept-invite/{institution}/{group}}', [InvitationController::class, 'acceptInvite'])
    ->name('accept-invite');

// reject invite
Route::get('reject-invite/{institution}/{group}', [InvitationController::class, 'rejectInvite'])
    ->name('reject-invite');
