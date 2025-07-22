<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiYandexDirectSetting;
use App\Models\ApiYandexDirectAgencyAccount;
use App\Models\ApiYandexDirectAccount;

class YandexDirectApiService
{
    private $baseUrl = 'https://api.direct.yandex.com/json/v5/';
    private $oauthUrl = 'https://oauth.yandex.ru/';
    private $settings;

    public function __construct()
    {
        $this->settings = ApiYandexDirectSetting::first();
    }

    /**
     * Получить URL для OAuth авторизации
     */
    public function getOAuthUrl($state = null)
    {
        if (!$this->settings) {
            throw new \Exception('Настройки Яндекс.Директ не найдены');
        }

        $params = [
            'response_type' => 'code',
            'client_id' => $this->settings->client_id,
            'redirect_uri' => $this->settings->redirect_uri,
        ];

        if ($state) {
            $params['state'] = $state;
        }

        return $this->oauthUrl . 'authorize?' . http_build_query($params);
    }

    /**
     * Получить OAuth токен по коду авторизации
     */
    public function getOAuthToken($code)
    {
        if (!$this->settings) {
            throw new \Exception('Настройки Яндекс.Директ не найдены');
        }

        $response = Http::asForm()->post($this->oauthUrl . 'token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->settings->client_id,
            'client_secret' => $this->settings->client_secret,
            'redirect_uri' => $this->settings->redirect_uri,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'] ?? null;
        }

        Log::error('Ошибка получения OAuth токена', [
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось получить OAuth токен: ' . $response->body());
    }

    /**
     * Обновить OAuth токен
     */
    public function refreshOAuthToken($refreshToken)
    {
        if (!$this->settings) {
            throw new \Exception('Настройки Яндекс.Директ не найдены');
        }

        $response = Http::asForm()->post($this->oauthUrl . 'token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->settings->client_id,
            'client_secret' => $this->settings->client_secret,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'] ?? null;
        }

        Log::error('Ошибка обновления OAuth токена', [
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось обновить OAuth токен: ' . $response->body());
    }

    /**
     * Получить список аккаунтов клиента
     */
    public function getAccounts($oauthToken)
    {
        Log::info('=== НАЧАЛО getAccounts в API сервисе ===');
        Log::info('1. OAuth токен получен', ['token_length' => strlen($oauthToken)]);
        Log::info('2. URL запроса', ['url' => $this->baseUrl . 'clients']);
        
        // Проверяем валидность токена
        if (!$this->validateToken($oauthToken)) {
            Log::error('OAuth токен недействителен');
            throw new \Exception('OAuth токен недействителен или истек');
        }
        
        Log::info('3. OAuth токен валиден');
        
        $requestData = [
            'method' => 'get',
            'params' => [
                'SelectionCriteria' => (object)[],
                'FieldNames' => ['Login', 'AccountId', 'AccountName', 'Status', 'AccountType']
            ]
        ];
        
        Log::info('4. Данные запроса', ['data' => $requestData]);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $oauthToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'ru-RU,ru;q=0.9,en;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Cache-Control' => 'no-cache',
                'Pragma' => 'no-cache'
            ])->post($this->baseUrl . 'clients', $requestData);
            
            Log::info('5. HTTP запрос выполнен', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'headers' => $response->headers()
            ]);
            
            Log::info('6. Тело ответа', ['body' => $response->body()]);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('7. JSON ответ', ['data' => $data]);
                
                // Проверяем на ошибки API
                if (isset($data['error'])) {
                    $error = $data['error'];
                    Log::error('8. Ошибка API Яндекс.Директ', [
                        'error_code' => $error['error_code'] ?? 'unknown',
                        'error_string' => $error['error_string'] ?? 'unknown',
                        'error_detail' => $error['error_detail'] ?? 'unknown'
                    ]);
                    
                    if ($error['error_code'] == 58) {
                        throw new \Exception('Приложение не зарегистрировано в Яндекс.Директ. Необходимо заполнить заявку на доступ к API в интерфейсе Яндекс.Директ.');
                    }
                    
                    throw new \Exception('Ошибка API Яндекс.Директ: ' . ($error['error_string'] ?? 'Неизвестная ошибка'));
                }
                
                $clients = $data['result']['Clients'] ?? [];
                Log::info('9. Извлеченные клиенты', [
                    'count' => count($clients),
                    'clients' => $clients
                ]);
                
                return $clients;
            }
            
            Log::error('10. HTTP запрос неуспешен', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            throw new \Exception('Не удалось получить список аккаунтов: ' . $response->body());
            
        } catch (\Exception $e) {
            Log::error('11. Исключение в getAccounts', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }

    /**
     * Получить информацию об аккаунте
     */
    public function getAccountInfo($oauthToken, $login)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'agencyclients', [
            'method' => 'get',
            'params' => [
                'SelectionCriteria' => [
                    'Logins' => [$login]
                ],
                'FieldNames' => ['Login', 'AccountId', 'AccountName', 'Status', 'AccountType']
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['result']['Clients'][0] ?? null;
        }

        Log::error('Ошибка получения информации об аккаунте', [
            'login' => $login,
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось получить информацию об аккаунте: ' . $response->body());
    }

    /**
     * Проверить валидность OAuth токена
     */
    public function validateToken($oauthToken)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $oauthToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'agencyclients', [
                'method' => 'get',
                'params' => [
                    'SelectionCriteria' => (object)[],
                    'FieldNames' => ['Login']
                ]
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Ошибка валидации токена', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Получить статистику по кампаниям
     */
    public function getCampaignsStats($oauthToken, $dateFrom, $dateTo, $logins = [])
    {
        $params = [
            'SelectionCriteria' => [
                'DateFrom' => $dateFrom,
                'DateTo' => $dateTo,
            ],
            'FieldNames' => [
                'CampaignId',
                'CampaignName',
                'Impressions',
                'Clicks',
                'Cost',
                'Ctr',
                'AvgCpc'
            ]
        ];

        if (!empty($logins)) {
            $params['SelectionCriteria']['Logins'] = $logins;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'reports', [
            'method' => 'get',
            'params' => $params
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['result']['Records'] ?? [];
        }

        Log::error('Ошибка получения статистики кампаний', [
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось получить статистику кампаний: ' . $response->body());
    }

    /**
     * Создать кампанию
     */
    public function createCampaign($oauthToken, $campaignData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'campaigns', [
            'method' => 'add',
            'params' => [
                'Campaigns' => [$campaignData]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['result']['AddResults'][0] ?? null;
        }

        Log::error('Ошибка создания кампании', [
            'campaign_data' => $campaignData,
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось создать кампанию: ' . $response->body());
    }

    /**
     * Получить список кампаний
     */
    public function getCampaigns($oauthToken, $logins = [])
    {
        $params = [
            'SelectionCriteria' => (object)[],
            'FieldNames' => [
                'Id',
                'Name',
                'StartDate',
                'EndDate',
                'Status',
                'StatusPayment',
                'StatusClarification',
                'DailyBudget',
                'Currency'
            ]
        ];

        if (!empty($logins)) {
            $params['SelectionCriteria'] = ['Logins' => $logins];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'campaigns', [
            'method' => 'get',
            'params' => $params
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['result']['Campaigns'] ?? [];
        }

        Log::error('Ошибка получения списка кампаний', [
            'response' => $response->body(),
            'status' => $response->status()
        ]);

        throw new \Exception('Не удалось получить список кампаний: ' . $response->body());
    }
} 