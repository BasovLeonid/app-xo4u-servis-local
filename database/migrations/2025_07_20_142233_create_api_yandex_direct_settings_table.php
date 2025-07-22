<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_yandex_direct_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_login')->default('mandain5@yandex.ru');
            $table->string('app_name')->default('xo4u-servis');
            $table->string('app_url')->default('https://oauth.yandex.ru/client/4b516980adea471abbe91d5e9b7d6634');
            $table->string('client_id')->default('4b516980adea471abbe91d5e9b7d6634');
            $table->string('client_secret')->default('8d0267598b0340adb0d751700ba7eaf7');
            $table->string('redirect_uri')->default('https://oauth.yandex.ru/verification_code');
            $table->string('auth_url')->default('https://oauth.yandex.ru/authorize?response_type=token&client_id=4b516980adea471abbe91d5e9b7d6634');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_yandex_direct_settings');
    }
};
