<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PayPalLoginController;
use App\Http\Controllers\Auth\RegisterArtist\RegisteredArtistController;
// use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisterStudio\SubscriptionController;
use App\Http\Controllers\Auth\RegisterStudio\BusinessController;
use App\Http\Controllers\Auth\RegisterStudio\StarterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //Google OAuth
    Route::get('google/auth/redirect', [GoogleLoginController::class, 'redirect'])->name('google.redirect');
    Route::get('google/auth/callback', [GoogleLoginController::class, 'callback'])->name('google.callback');

    //registrazione studio: scelta piano abbonamento
    Route::get('registrazione/studio/abbonamento', [SubscriptionController::class, 'choose_plan'])->name('register.studio.choose_plan');

    //registrazione abbonamento Starter
    Route::get('registrazione/studio/starter/step-1', [StarterController::class, 'step_1'])->name('register.studio.starter.step_1');
    Route::get('registrazione/studio/starter/step-2', [StarterController::class, 'step_2'])->name('register.studio.starter.step_2');
    Route::get('registrazione/studio/starter/step-3', [StarterController::class, 'step_3'])->name('register.studio.starter.step_3');
    Route::post('registrazione/studio/starter', [StarterController::class, 'store'])->name('register.studio.starter.store');

    //registrazione abbonamento Business
    Route::get('registrazione/studio/business/step-1', [BusinessController::class, 'step_1'])->name('register.studio.business.step_1');
    Route::get('registrazione/studio/business/step-2', [BusinessController::class, 'step_2'])->name('register.studio.business.step_2');
    Route::get('registrazione/studio/business/step-3', [BusinessController::class, 'step_3'])->name('register.studio.business.step_3');
    Route::post('registrazione/studio/business', [BusinessController::class, 'store'])->name('register.studio.business.store');

    //registrazione artista
    Route::get('registrazione/artista', [RegisteredArtistController::class, 'create'])->name('register');
    Route::post('registrazione/artista', [RegisteredArtistController::class, 'store'])->name('register.artist.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('google/auth/revoke', [GoogleLoginController::class, 'revoke_token'])->name('google.revoke');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //PayPal OAuth
    Route::get('/paypal/auth/redirect', [PayPalLoginController::class, 'redirect'])->name('paypal.redirect');
    Route::get('/paypal/auth/callback', [PayPalLoginController::class, 'callback'])->name('paypal.callback');
    Route::patch('/paypal/auth/unlink', [PayPalLoginController::class, 'unlink'])->name('paypal.unlink');
});

