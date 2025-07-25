<?php

namespace App\Services;

use Biplane\YandexDirect\Api\V5\Contract\ClientsInterface;
use Biplane\YandexDirect\Api\V5\Contract\GetClientsRequest;
use Biplane\YandexDirect\Api\V5\Contract\ClientSelectionCriteria;
use Biplane\YandexDirect\Api\V5\Contract\ClientFieldEnum;
use Biplane\YandexDirect\Api\V5\Contract\AgencyClientsInterface;
use Biplane\YandexDirect\Api\V5\Contract\GetAgencyClientsRequest;
use Biplane\YandexDirect\Api\V5\Contract\AgencyClientSelectionCriteria;
use Biplane\YandexDirect\Api\V5\Contract\AgencyClientFieldEnum;
use Illuminate\Support\Facades\Log;

class YandexDirectBiplaneService
{
    private $clientsApi;
    private $agencyClientsApi;
    
    public function __construct(ClientsInterface $clientsApi, AgencyClientsInterface $agencyClientsApi)
    {
        $this->clientsApi = $clientsApi;
        $this->agencyClientsApi = $agencyClientsApi;
    }
    
    /**
     * Получить список клиентских аккаунтов
     */
    public function getClients($oauthToken)
    {
        Log::info('=== НАЧАЛО getClients в Biplane сервисе ===');
        Log::info('1. OAuth токен получен', ['token_length' => strlen($oauthToken)]);
        
        try {
            // Устанавливаем OAuth токен
            $this->clientsApi->setAccessToken($oauthToken);
            
            // Создаем критерии выборки (пустые - получаем всех клиентов)
            $selectionCriteria = new ClientSelectionCriteria();
            
            // Указываем поля, которые хотим получить
            $fieldNames = [
                ClientFieldEnum::LOGIN,
                ClientFieldEnum::ACCOUNT_ID,
                ClientFieldEnum::ACCOUNT_NAME,
                ClientFieldEnum::STATUS,
                ClientFieldEnum::ACCOUNT_TYPE
            ];
            
            Log::info('2. Поля для получения', ['fields' => $fieldNames]);
            
            // Создаем запрос
            $request = new GetClientsRequest();
            $request->setSelectionCriteria($selectionCriteria);
            $request->setFieldNames($fieldNames);
            
            Log::info('3. Отправляем запрос через Biplane API');
            
            // Отправляем запрос
            $response = $this->clientsApi->get($request);
            
            Log::info('4. Получен ответ от API');
            
            // Получаем клиентов из ответа
            $clients = $response->getClients();
            
            Log::info('5. Извлечены клиенты', [
                'count' => count($clients),
                'clients' => $clients
            ]);
            
            return $clients;
            
        } catch (\Exception $e) {
            Log::error('6. Ошибка в getClients', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw new \Exception('Ошибка получения клиентов через Biplane: ' . $e->getMessage());
        }
    }
    
    /**
     * Получить список агентских клиентов
     */
    public function getAgencyClients($oauthToken)
    {
        Log::info('=== НАЧАЛО getAgencyClients в Biplane сервисе ===');
        Log::info('1. OAuth токен получен', ['token_length' => strlen($oauthToken)]);
        
        try {
            // Устанавливаем OAuth токен
            $this->agencyClientsApi->setAccessToken($oauthToken);
            
            // Создаем критерии выборки
            $selectionCriteria = new AgencyClientSelectionCriteria();
            
            // Указываем поля
            $fieldNames = [
                AgencyClientFieldEnum::LOGIN,
                AgencyClientFieldEnum::ACCOUNT_ID,
                AgencyClientFieldEnum::ACCOUNT_NAME,
                AgencyClientFieldEnum::STATUS,
                AgencyClientFieldEnum::ACCOUNT_TYPE
            ];
            
            Log::info('2. Поля для получения', ['fields' => $fieldNames]);
            
            // Создаем запрос
            $request = new GetAgencyClientsRequest();
            $request->setSelectionCriteria($selectionCriteria);
            $request->setFieldNames($fieldNames);
            
            Log::info('3. Отправляем запрос через Biplane API');
            
            // Отправляем запрос
            $response = $this->agencyClientsApi->get($request);
            
            Log::info('4. Получен ответ от API');
            
            // Получаем клиентов из ответа
            $clients = $response->getClients();
            
            Log::info('5. Извлечены агентские клиенты', [
                'count' => count($clients),
                'clients' => $clients
            ]);
            
            return $clients;
            
        } catch (\Exception $e) {
            Log::error('6. Ошибка в getAgencyClients', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw new \Exception('Ошибка получения агентских клиентов через Biplane: ' . $e->getMessage());
        }
    }
}
