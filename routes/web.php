<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearningCertificateController;

Route::get('/', function () {
    return view('welcome');
});

// certificate pdfs
Route::get('/learning-resources/{learningResource}/pdf', [LearningCertificateController::class, 'index'])->name('learning-resources.pdf');
