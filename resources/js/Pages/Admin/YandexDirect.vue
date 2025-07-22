<script setup>
import { Head } from '@inertiajs/vue3'
import { 
    CogIcon,
    UserIcon,
    KeyIcon,
    GlobeAltIcon,
    LinkIcon
} from '@heroicons/vue/24/outline'

defineProps({
    auth: {
        type: Object,
        required: true
    },
    settings: {
        type: Object,
        default: null
    },
    accounts: {
        type: Array,
        default: () => []
    }
})
</script>

<template>
    <Head title="Яндекс Директ - Администратор" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Яндекс Директ</h1>
        <p class="mt-2 text-gray-600">Управление настройками API и аккаунтами</p>
    </div>

    <div class="max-w-4xl space-y-6">
        <!-- Настройки API -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <CogIcon class="w-6 h-6 text-blue-600 mr-3" />
                    <h2 class="text-xl font-semibold text-gray-900">Настройки API</h2>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Аккаунт приложения -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Аккаунт приложения для доступа к API Яндекс Директ</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <UserIcon class="w-5 h-5 text-gray-400 mr-2" />
                            <span class="text-sm font-medium text-gray-700">Логин:</span>
                            <span class="ml-2 text-sm text-gray-900">{{ settings?.app_login || 'mandain5@yandex.ru' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Данные приложения -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Данные приложения</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <GlobeAltIcon class="w-5 h-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-700">Название приложения:</span>
                            </div>
                            <span class="text-sm text-gray-900">{{ settings?.app_name || 'xo4u-servis' }}</span>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <LinkIcon class="w-5 h-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-700">URL приложения:</span>
                            </div>
                            <a :href="settings?.app_url || 'https://oauth.yandex.ru/client/4b516980adea471abbe91d5e9b7d6634'" 
                               target="_blank" 
                               class="text-sm text-blue-600 hover:text-blue-800 break-all">
                                {{ settings?.app_url || 'https://oauth.yandex.ru/client/4b516980adea471abbe91d5e9b7d6634' }}
                            </a>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <KeyIcon class="w-5 h-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-700">Client ID:</span>
                            </div>
                            <span class="text-sm text-gray-900 font-mono">{{ settings?.client_id || '4b516980adea471abbe91d5e9b7d6634' }}</span>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <KeyIcon class="w-5 h-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-700">Client Secret:</span>
                            </div>
                            <span class="text-sm text-gray-900 font-mono">{{ settings?.client_secret || '8d0267598b0340adb0d751700ba7eaf7' }}</span>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <LinkIcon class="w-5 h-5 text-gray-400 mr-2" />
                                <span class="text-sm font-medium text-gray-700">Redirect URI:</span>
                            </div>
                            <span class="text-sm text-gray-900 break-all">{{ settings?.redirect_uri || 'https://oauth.yandex.ru/verification_code' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ссылка на предоставление доступа -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ссылка на предоставление доступа</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800 mb-3">
                            Для получения токена вручную выполните следующие действия:
                        </p>
                        <ol class="text-sm text-blue-800 space-y-1 mb-4">
                            <li>1. Войдите на Яндекс под своим логином</li>
                            <li>2. Перейдите по ссылке ниже</li>
                        </ol>
                        <a :href="settings?.auth_url || 'https://oauth.yandex.ru/authorize?response_type=token&client_id=4b516980adea471abbe91d5e9b7d6634'" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <LinkIcon class="w-4 h-4 mr-2" />
                            Получить OAuth токен
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Аккаунты -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <UserIcon class="w-6 h-6 text-blue-600 mr-3" />
                        <h2 class="text-xl font-semibold text-gray-900">Аккаунты</h2>
                    </div>
                    <span class="text-sm text-gray-500">{{ accounts.length }} аккаунтов</span>
                </div>
            </div>
            
            <div class="p-6">
                <div v-if="accounts.length === 0" class="text-center py-8">
                    <UserIcon class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Нет аккаунтов</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Аккаунты Яндекс.Директ еще не добавлены.
                    </p>
                </div>
                
                <div v-else class="space-y-4">
                    <div v-for="account in accounts" :key="account.id" class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-900">{{ account.yandex_login }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-green-100 text-green-800': account.account_type === 'users',
                                              'bg-blue-100 text-blue-800': account.account_type === 'templates'
                                          }">
                                        {{ account.account_type === 'users' ? 'Пользователи' : 'Шаблоны' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-purple-100 text-purple-800': account.yandex_type_account === 'agency',
                                              'bg-gray-100 text-gray-800': account.yandex_type_account === 'user'
                                          }">
                                        {{ account.yandex_type_account === 'agency' ? 'Агентский' : 'Пользовательский' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Пользователь: {{ account.user?.name || 'Неизвестно' }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">
                                    {{ account.oauth_token ? 'Токен есть' : 'Токен отсутствует' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 