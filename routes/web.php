<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    // Панель управления - единый роутинг
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/accounts', 'accounts')->name('accounts');
        Route::get('/statistics', 'statistics')->name('statistics');
        Route::get('/leads', 'leads')->name('leads');
        Route::get('/profile', 'profile')->name('profile.edit');
        Route::get('/balance', 'balance')->name('balance');
        
        // Административные маршруты
        Route::prefix('admin')->group(function () {
            Route::get('/yandex-direct', 'yandexDirect')->name('admin.yandex-direct');
            Route::get('/yandex-direct/agency-accounts', 'yandexDirectAgencyAccounts')->name('admin.yandex-direct.agency-accounts');
            Route::post('/yandex-direct/agency-accounts', 'storeYandexDirectAgencyAccount')->name('admin.yandex-direct.agency-accounts.store');
            Route::get('/yandex-direct/client-accounts', 'yandexDirectClientAccounts')->name('admin.yandex-direct.client-accounts');
            Route::get('/yandex-direct/templates', 'yandexDirectTemplates')->name('admin.yandex-direct.templates');
        });
    });
    
    // Yandex Direct OAuth Routes
    Route::prefix('yandex-direct/oauth')->name('yandex-direct.oauth.')->group(function () {
        Route::post('/get-url', [App\Http\Controllers\YandexDirectOAuthController::class, 'getOAuthUrl'])->name('get-url');
        Route::get('/callback', [App\Http\Controllers\YandexDirectOAuthController::class, 'handleCallback'])->name('callback');
        Route::post('/validate-token', [App\Http\Controllers\YandexDirectOAuthController::class, 'validateToken'])->name('validate-token');
        Route::post('/account-info', [App\Http\Controllers\YandexDirectOAuthController::class, 'getAccountInfo'])->name('account-info');
        Route::post('/accounts', [App\Http\Controllers\YandexDirectOAuthController::class, 'getAccounts'])->name('accounts');
        Route::post('/accounts-biplane', [App\Http\Controllers\YandexDirectOAuthController::class, 'getAccountsBiplane'])->name('accounts-biplane');
    });
    
    // Маршруты для обновления профиля
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
