<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Biplane\YandexDirect\Api\V5\Contract\ClientsInterface;
use Biplane\YandexDirect\Api\V5\Contract\AgencyClientsInterface;
use Biplane\YandexDirect\Api\V5\Clients;
use Biplane\YandexDirect\Api\V5\AgencyClients;
use Biplane\YandexDirect\Api\V5\Contract\SoapClientFactoryInterface;
use Biplane\YandexDirect\Api\V5\SoapClientFactory;
use App\Services\YandexDirectBiplaneService;

class YandexDirectBiplaneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Регистрируем SOAP клиент фабрику
        $this->app->singleton(SoapClientFactoryInterface::class, function ($app) {
            return new SoapClientFactory();
        });
        
        // Регистрируем API клиентов
        $this->app->singleton(ClientsInterface::class, function ($app) {
            $factory = $app->make(SoapClientFactoryInterface::class);
            return new Clients($factory);
        });
        
        // Регистрируем API агентских клиентов
        $this->app->singleton(AgencyClientsInterface::class, function ($app) {
            $factory = $app->make(SoapClientFactoryInterface::class);
            return new AgencyClients($factory);
        });
        
        // Регистрируем наш сервис
        $this->app->singleton(YandexDirectBiplaneService::class, function ($app) {
            return new YandexDirectBiplaneService(
                $app->make(ClientsInterface::class),
                $app->make(AgencyClientsInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
