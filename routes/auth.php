<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/register/{lang?}', [RegisteredUserController::class, 'showRegistrationForm'])
                ->middleware('guest')
                ->name('register');

Route::get('client/clien_reg_payment', [RegisteredUserController::class, 'showClientPaymentPage'])
    ->middleware('guest');

Route::post('/register', [RegisteredUserController::class, 'postClientRegistrationForm'])
                ->middleware('guest');

Route::get('/verify{lang?}', [RegisteredUserController::class, 'verifyClientEmail'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware(['guest','isAdmin']);

Route::get('/forgot-password', [AuthenticatedSessionController::class, 'showLinkRequestForm'])
                ->middleware('guest')
                ->name('password.request');

Route::get('/client-forgot-password', [AuthenticatedSessionController::class, 'clientShowLinkRequestForm'])
//    ->middleware('guest ')
    ->name('client-password-request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::post('/client-forgot-password', [PasswordResetLinkController::class, 'clientStore'])
    ->middleware('guest')
    ->name('client-password-email');


Route::get('/client-reset-password/{token?}/{lang?}', [NewPasswordController::class, 'clientCreate'])
    ->middleware('guest')
    ->name('client-password-reset');

Route::post('/client-reset-password', [NewPasswordController::class, 'clientStore'])
    ->middleware('guest')
    ->name('client-password-update');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');


Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::get('/resend/verification/{slug}/{client}', [RegisteredUserController::class, 'clientResendVerificationLink'])
    ->middleware('guest')
    ->name('client-resend-verivication-link');

// Laravel 8 & 9
Route::post('/pay', [App\Http\Controllers\PaymentController::class, 'redirectToGateway'])->name('pay');
// Laravel 8 & 9
Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleGatewayCallback']);

Route::post('/flutter-wave', [App\Http\Controllers\PaymentController::class, 'verifyPay'])->name('payWithFlutter');
