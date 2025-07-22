<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard');
    }

    public function accounts()
    {
        return Inertia::render('Accounts');
    }

    public function statistics()
    {
        return Inertia::render('Statistics');
    }

    public function leads()
    {
        return Inertia::render('Leads');
    }

    public function profile()
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => config('auth.must_verify_email'),
            'status' => session('status'),
        ]);
    }

    public function balance()
    {
        return Inertia::render('Balance');
    }

    public function yandexDirect()
    {
        $settings = \App\Models\ApiYandexDirectSetting::first();
        $accounts = \App\Models\ApiYandexDirectAccount::with('user')->get();
        
        return Inertia::render('Admin/YandexDirect', [
            'settings' => $settings,
            'accounts' => $accounts
        ]);
    }

    public function yandexDirectAgencyAccounts()
    {
        $agencyAccounts = \App\Models\ApiYandexDirectAgencyAccount::with('user')->get();
        
        return Inertia::render('Admin/YandexDirectAgencyAccounts', [
            'agencyAccounts' => $agencyAccounts
        ]);
    }

    public function yandexDirectClientAccounts()
    {
        $clientAccounts = \App\Models\ApiYandexDirectAccount::with(['user', 'agencyAccount'])->get();
        
        return Inertia::render('Admin/YandexDirectClientAccounts', [
            'clientAccounts' => $clientAccounts
        ]);
    }

    public function storeYandexDirectAgencyAccount(Request $request)
    {
        $request->validate([
            'yandex_login' => 'required|string|max:255',
            'yandex_password' => 'required|string|max:255',
            'type' => 'required|in:own,partner',
            'oauth_token' => 'nullable|string'
        ]);

        \App\Models\ApiYandexDirectAgencyAccount::create([
            'type' => $request->type,
            'user_id' => $request->type === 'partner' ? auth()->id() : null,
            'yandex_login' => $request->yandex_login,
            'yandex_password' => $request->yandex_password,
            'oauth_token' => $request->oauth_token,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Агентский аккаунт успешно добавлен');
    }

    public function yandexDirectTemplates()
    {
        return Inertia::render('Admin/YandexDirectTemplates');
    }
} 