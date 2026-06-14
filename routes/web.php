<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Language Switch
Route::post('/locale', function (Request $request) {
    $locale = $request->input('locale', 'zh');
    
    if (in_array($locale, ['zh', 'en'])) {
        app()->setLocale($locale);
        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
    
    return redirect()->back();
})->name('locale.switch');

// Auth Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [LoginController::class, 'show'])->name('login');
    Route::post('/auth/login', [LoginController::class, 'store']);
});

// Auth Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Protected Routes (需要登录)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Auth Error Pages (Public)
Route::get('/auth/error', fn () => Inertia::render('Auth/Error'))->name('auth.error');
Route::get('/auth/access', fn () => Inertia::render('Auth/Access'))->name('auth.access');
