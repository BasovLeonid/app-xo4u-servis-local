<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiYandexDirectSetting extends Model
{
    protected $fillable = [
        'app_login',
        'app_name',
        'app_url',
        'client_id',
        'client_secret',
        'redirect_uri',
        'auth_url'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
