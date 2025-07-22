<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YandexDirectApiService;
use App\Services\YandexDirectBiplaneService;
use App\Models\ApiYandexDirectAgencyAccount;
use App\Models\ApiYandexDirectAccount;

class YandexDirectOAuthController extends Controller
{
    private $apiService;
    private $biplaneService;

    public function __construct(YandexDirectApiService $apiService, YandexDirectBiplaneService $biplaneService)
    {
        $this->apiService = $apiService;
        $this->biplaneService = $biplaneService;
    }

    /**
     * Получить URL для OAuth авторизации
     */
    public function getOAuthUrl(Request $request)
    {
        try {
            $accountId = $request->input('account_id');
            $accountType = $request->input('account_type'); // 'agency' или 'client'
            
            $state = json_encode([
                'account_id' => $accountId,
                'account_type' => $accountType
            ]);

            $oauthUrl = $this->apiService->getOAuthUrl($state);
            
            return response()->json([
                'success' => true,
                'oauth_url' => $oauthUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Обработка callback от Яндекс OAuth
     */
    public function handleCallback(Request $request)
    {
        try {
            $code = $request->input('code');
            $state = $request->input('state');
            
            if (!$code) {
                throw new \Exception('Код авторизации не получен');
            }

            // Получаем OAuth токен
            $oauthToken = $this->apiService->getOAuthToken($code);
            
            if (!$oauthToken) {
                throw new \Exception('Не удалось получить OAuth токен');
            }

            // Декодируем state для получения информации об аккаунте
            $stateData = json_decode($state, true);
            $accountId = $stateData['account_id'] ?? null;
            $accountType = $stateData['account_type'] ?? null;

            if ($accountId && $accountType) {
                // Обновляем токен в соответствующей таблице
                if ($accountType === 'agency') {
                    $account = ApiYandexDirectAgencyAccount::find($accountId);
                    if ($account) {
                        $account->update(['oauth_token' => $oauthToken]);
                    }
                } elseif ($accountType === 'client') {
                    $account = ApiYandexDirectAccount::find($accountId);
                    if ($account) {
                        $account->update(['oauth_token' => $oauthToken]);
                    }
                }
            }

            return redirect()->route('admin.yandex-direct.agency-accounts')
                ->with('success', 'OAuth токен успешно получен и сохранен');

        } catch (\Exception $e) {
            return redirect()->route('admin.yandex-direct.agency-accounts')
                ->with('error', 'Ошибка получения OAuth токена: ' . $e->getMessage());
        }
    }

    /**
     * Проверить валидность токена
     */
    public function validateToken(Request $request)
    {
        try {
            $oauthToken = $request->input('oauth_token');
            
            if (!$oauthToken) {
                return response()->json([
                    'success' => false,
                    'error' => 'OAuth токен не предоставлен'
                ], 400);
            }

            $isValid = $this->apiService->validateToken($oauthToken);
            
            return response()->json([
                'success' => true,
                'is_valid' => $isValid
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получить информацию об аккаунте
     */
    public function getAccountInfo(Request $request)
    {
        try {
            $oauthToken = $request->input('oauth_token');
            $login = $request->input('login');
            
            if (!$oauthToken || !$login) {
                return response()->json([
                    'success' => false,
                    'error' => 'OAuth токен и логин обязательны'
                ], 400);
            }

            $accountInfo = $this->apiService->getAccountInfo($oauthToken, $login);
            
            return response()->json([
                'success' => true,
                'account_info' => $accountInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получить список аккаунтов клиента
     */
    public function getAccounts(Request $request)
    {
        try {
            \Log::info('=== НАЧАЛО getAccounts в контроллере ===');
            \Log::info('1. Полный запрос', ['data' => $request->all()]);
            \Log::info('2. Метод запроса', ['method' => $request->method()]);
            \Log::info('3. URL запроса', ['url' => $request->url()]);
            \Log::info('4. Заголовки запроса', ['headers' => $request->headers->all()]);
            
            $accountId = $request->input('account_id');
            \Log::info('5. accountId из запроса', ['accountId' => $accountId]);
            
            if (!$accountId) {
                \Log::error('6. Account ID отсутствует');
                return back()->withErrors(['message' => 'ID аккаунта обязателен']);
            }

            // Получаем OAuth токен из базы данных
            \Log::info('7. Ищем агентский аккаунт в БД');
            $agencyAccount = \App\Models\ApiYandexDirectAgencyAccount::find($accountId);
            
            if (!$agencyAccount) {
                \Log::error('8. Агентский аккаунт не найден', ['account_id' => $accountId]);
                return back()->withErrors(['message' => 'Агентский аккаунт не найден']);
            }
            
            \Log::info('9. Агентский аккаунт найден', [
                'id' => $agencyAccount->id,
                'login' => $agencyAccount->yandex_login,
                'has_oauth_token' => !empty($agencyAccount->oauth_token),
                'oauth_token_length' => strlen($agencyAccount->oauth_token ?? '')
            ]);

            if (!$agencyAccount->oauth_token) {
                \Log::error('10. OAuth токен не настроен для аккаунта', ['account_id' => $accountId]);
                return back()->withErrors(['message' => 'OAuth токен не настроен для этого аккаунта']);
            }

            \Log::info('11. Вызываем API сервис getAccounts');
            $accounts = $this->apiService->getAccounts($agencyAccount->oauth_token);
            
            \Log::info('12. Аккаунты получены от API', [
                'count' => is_array($accounts) ? count($accounts) : 'не массив',
                'type' => gettype($accounts),
                'accounts' => $accounts
            ]);
            
            \Log::info('13. Возвращаем данные через flash сообщение');
            $response = back()->with('accounts', $accounts);
            \Log::info('14. Ответ создан, возвращаем');
            
            return $response;

        } catch (\Exception $e) {
            \Log::error('=== ОШИБКА в getAccounts ===');
            \Log::error('15. Сообщение ошибки', ['error' => $e->getMessage()]);
            \Log::error('16. Файл ошибки', ['file' => $e->getFile()]);
            \Log::error('17. Строка ошибки', ['line' => $e->getLine()]);
            \Log::error('18. Stack trace', ['trace' => $e->getTraceAsString()]);
            
            return back()->withErrors(['message' => 'Ошибка получения аккаунтов: ' . $e->getMessage()]);
        }
    }

    /**
     * Получить список аккаунтов клиента через Biplane
     */
    public function getAccountsBiplane(Request $request)
    {
        try {
            \Log::info('=== НАЧАЛО getAccountsBiplane в контроллере ===');
            \Log::info('1. Полный запрос', ['data' => $request->all()]);
            
            $accountId = $request->input('account_id');
            \Log::info('2. accountId из запроса', ['accountId' => $accountId]);
            
            if (!$accountId) {
                \Log::error('3. Account ID отсутствует');
                return back()->withErrors(['message' => 'ID аккаунта обязателен']);
            }

            // Получаем OAuth токен из базы данных
            \Log::info('4. Ищем агентский аккаунт в БД');
            $agencyAccount = \App\Models\ApiYandexDirectAgencyAccount::find($accountId);
            
            if (!$agencyAccount) {
                \Log::error('5. Агентский аккаунт не найден', ['account_id' => $accountId]);
                return back()->withErrors(['message' => 'Агентский аккаунт не найден']);
            }
            
            \Log::info('6. Агентский аккаунт найден', [
                'id' => $agencyAccount->id,
                'login' => $agencyAccount->yandex_login,
                'has_oauth_token' => !empty($agencyAccount->oauth_token),
                'oauth_token_length' => strlen($agencyAccount->oauth_token ?? '')
            ]);

            if (!$agencyAccount->oauth_token) {
                \Log::error('7. OAuth токен не настроен для аккаунта', ['account_id' => $accountId]);
                return back()->withErrors(['message' => 'OAuth токен не настроен для этого аккаунта']);
            }

            \Log::info('8. Вызываем Biplane API сервис getClients');
            $accounts = $this->biplaneService->getClients($agencyAccount->oauth_token);
            
            \Log::info('9. Аккаунты получены от Biplane API', [
                'count' => is_array($accounts) ? count($accounts) : 'не массив',
                'type' => gettype($accounts),
                'accounts' => $accounts
            ]);
            
            \Log::info('10. Возвращаем данные через flash сообщение');
            $response = back()->with('accounts', $accounts);
            \Log::info('11. Ответ создан, возвращаем');
            
            return $response;

        } catch (\Exception $e) {
            \Log::error('=== ОШИБКА в getAccountsBiplane ===');
            \Log::error('12. Сообщение ошибки', ['error' => $e->getMessage()]);
            \Log::error('13. Файл ошибки', ['file' => $e->getFile()]);
            \Log::error('14. Строка ошибки', ['line' => $e->getLine()]);
            \Log::error('15. Stack trace', ['trace' => $e->getTraceAsString()]);
            
            return back()->withErrors(['message' => 'Ошибка получения аккаунтов через Biplane: ' . $e->getMessage()]);
        }
    }
}
