<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiYandexDirectAccount extends Model
{
    protected $fillable = [
        'yandex_login',
        'yandex_password',
        'account_type',
        'user_id',
        'agency_binding',
        'agency_account_id',
        'agency_login',
        'agency_oauth_token',
        'oauth_token',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agencyAccount()
    {
        return $this->belongsTo(ApiYandexDirectAgencyAccount::class, 'agency_account_id');
    }
}
