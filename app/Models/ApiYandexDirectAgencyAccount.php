<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiYandexDirectAgencyAccount extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'yandex_login',
        'yandex_password',
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

    public function clientAccounts()
    {
        return $this->hasMany(ApiYandexDirectAccount::class, 'agency_account_id');
    }
}
