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

    // UI Kit Documentation Pages
    Route::prefix('uikit')->name('uikit.')->group(function () {
        Route::get('formlayout', fn () => Inertia::render('Uikit/FormLayout'))->name('formlayout');
        Route::get('input', fn () => Inertia::render('Uikit/InputDoc'))->name('input');
        Route::get('button', fn () => Inertia::render('Uikit/ButtonDoc'))->name('button');
        Route::get('table', fn () => Inertia::render('Uikit/TableDoc'))->name('table');
        Route::get('list', fn () => Inertia::render('Uikit/ListDoc'))->name('list');
        Route::get('tree', fn () => Inertia::render('Uikit/TreeDoc'))->name('tree');
        Route::get('panels', fn () => Inertia::render('Uikit/PanelsDoc'))->name('panels');
        Route::get('overlay', fn () => Inertia::render('Uikit/OverlayDoc'))->name('overlay');
        Route::get('media', fn () => Inertia::render('Uikit/MediaDoc'))->name('media');
        Route::get('menu', fn () => Inertia::render('Uikit/MenuDoc'))->name('menu');
        Route::get('messages', fn () => Inertia::render('Uikit/MessagesDoc'))->name('messages');
        Route::get('misc', fn () => Inertia::render('Uikit/MiscDoc'))->name('misc');
        Route::get('chart', fn () => Inertia::render('Uikit/ChartDoc'))->name('chart');
        Route::get('timeline', fn () => Inertia::render('Uikit/TimelineDoc'))->name('timeline');
    });
});

// Auth Error Pages (Public)
Route::get('/auth/error', fn () => Inertia::render('Auth/Error'))->name('auth.error');
Route::get('/auth/access', fn () => Inertia::render('Auth/Access'))->name('auth.access');
