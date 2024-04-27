<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::post('/register/subscriber', [RegisteredUserController::class, 'storeSubscriber'])->name('register.subscriber');

Route::get('/register/subscriber/plans', [RegisteredUserController::class, 'stepTwo'])
                ->middleware(['auth'])
                ->name('register.subscriber.plans');

Route::post('/register/subscriber/plans/{id}', [RegisteredUserController::class, 'stepTwoStore'])
                ->middleware(['auth'])
                ->name('register.subscriber.plans.store');

Route::get('/register/subscriber/payment', [RegisteredUserController::class, 'stepThree'])
                ->middleware(['auth'])
                ->name('register.subscriber.payment');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/login/2fa', [AuthenticatedSessionController::class, 'twoFactorAuthentication'])
                ->name('login.2fa');

Route::post('/login/2fa', [AuthenticatedSessionController::class, 'twoFactorAuthenticationStore'])
                ->name('login.2fa.store');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

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

Route::post('/verify-email/confirm', [EmailVerificationNotificationController::class, 'check'])
                ->middleware(['auth', 'throttle:6,1']);

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::get('auth/redirect/{driver}', [SocialAuthController::class, 'redirectToProvider']);

Route::get('auth/callback/{driver}', [SocialAuthController::class, 'handleProviderCallback']);