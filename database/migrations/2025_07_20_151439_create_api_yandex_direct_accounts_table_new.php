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
        Schema::create('api_yandex_direct_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('yandex_login');
            $table->string('yandex_password');
            $table->enum('account_type', ['user', 'admin', 'templates'])->default('user'); // пользовательский / администраторский / шаблоны
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // не указывается, если аккаунт с шаблонами или администраторский
            $table->enum('agency_binding', ['bound', 'unbound'])->default('unbound'); // привязка аккаунта к агентству: привязан / не привязан
            $table->foreignId('agency_account_id')->nullable()->constrained('api_yandex_direct_agency_accounts')->onDelete('set null'); // id агентского аккаунта
            $table->string('agency_login')->nullable(); // логин агентства к которому привязан
            $table->text('agency_oauth_token')->nullable(); // OAuth-токен агентского аккаунта
            $table->text('oauth_token')->nullable(); // OAuth-токен индивидуального аккаунта, если аккаунт НЕ привязан к агентству
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_yandex_direct_accounts');
    }
};
