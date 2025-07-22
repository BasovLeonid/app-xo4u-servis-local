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
        Schema::create('api_yandex_direct_agency_accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['own', 'partner'])->default('own'); // Свои / Партнерские
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // user_id для партнерских аккаунтов
            $table->string('yandex_login');
            $table->string('yandex_password');
            $table->text('oauth_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_yandex_direct_agency_accounts');
    }
};
