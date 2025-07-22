<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { 
    BuildingOfficeIcon,
    UserIcon,
    PlusIcon,
    CogIcon,
    EyeIcon,
    PencilIcon,
    TrashIcon,
    KeyIcon,
    ExclamationTriangleIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    auth: {
        type: Object,
        required: true
    },
    agencyAccounts: {
        type: Array,
        default: () => []
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

const form = ref({
    yandex_login: '',
    yandex_password: '',
    type: 'own',
    oauth_token: ''
})

const resetForm = () => {
    form.value = {
        yandex_login: '',
        yandex_password: '',
        type: 'own',
        oauth_token: ''
    }
}

const accountsData = ref(null)
const isLoadingAccounts = ref(false)
const accountsError = ref(null)

const submitForm = () => {
    router.post('/admin/yandex-direct/agency-accounts', form.value, {
        onSuccess: () => {
            resetForm()
        }
    })
}

const getOAuthToken = async (login) => {
    try {
        const response = await fetch('/yandex-direct/oauth/get-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                               document.querySelector('input[name="_token"]')?.value ||
                               ''
            },
            body: JSON.stringify({
                account_id: null, // Будет установлен после создания аккаунта
                account_type: 'agency'
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Открываем окно авторизации
            window.open(data.oauth_url, 'yandex_oauth', 'width=600,height=700,scrollbars=yes,resizable=yes');
        } else {
            alert('Ошибка получения URL авторизации: ' + data.error);
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при получении URL авторизации');
    }
}



const getAccounts = async (accountId) => {
    console.log('=== НАЧАЛО getAccounts ===')
    console.log('1. accountId:', accountId)
    console.log('2. Текущее состояние isLoadingAccounts:', isLoadingAccounts.value)
    console.log('3. Текущее состояние accountsError:', accountsError.value)
    console.log('4. Текущее состояние accountsData:', accountsData.value)
    
    isLoadingAccounts.value = true
    accountsError.value = null
    accountsData.value = null
    
    console.log('5. Состояние после сброса - isLoadingAccounts:', isLoadingAccounts.value)
    console.log('6. Состояние после сброса - accountsError:', accountsError.value)
    console.log('7. Состояние после сброса - accountsData:', accountsData.value)
    
    try {
        console.log('8. Отправляем запрос через Inertia router...')
        
        // Используем Inertia router для правильной отправки CSRF токена
        router.post('/yandex-direct/oauth/accounts-biplane', {
            account_id: accountId
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('=== onSuccess CALLBACK ===')
                console.log('9. Полный объект page:', page)
                console.log('10. page.props:', page.props)
                console.log('11. page.props.flash:', page.props.flash)
                console.log('12. page.props.errors:', page.props.errors)
                
                isLoadingAccounts.value = false
                
                if (page.props.flash?.accounts) {
                    console.log('15. Найдены accounts в flash:', page.props.flash.accounts)
                    accountsData.value = page.props.flash.accounts
                } else if (page.props.flash?.error) {
                    console.log('16. Найдена ошибка в flash:', page.props.flash.error)
                    accountsError.value = page.props.flash.error
                } else {
                    console.log('17. Нет данных в flash, устанавливаем ошибку')
                    accountsError.value = 'Не удалось получить данные аккаунтов'
                }
                
                console.log('18. Финальное состояние accountsData:', accountsData.value)
                console.log('19. Финальное состояние accountsError:', accountsError.value)
                console.log('=== КОНЕЦ onSuccess ===')
            },
            onError: (errors) => {
                console.log('=== onError CALLBACK ===')
                console.log('20. Ошибка получена:', errors)
                console.log('21. Тип ошибки:', typeof errors)
                console.log('22. Ключи ошибки:', Object.keys(errors))
                
                isLoadingAccounts.value = false
                accountsError.value = errors.message || 'Произошла ошибка при получении аккаунтов'
                
                console.log('23. Финальное состояние accountsError:', accountsError.value)
                console.log('=== КОНЕЦ onError ===')
            }
        });
        
        console.log('9. Запрос отправлен, ожидаем callback...')
        
    } catch (error) {
        console.log('=== CATCH BLOCK ===')
        console.log('24. Поймана ошибка:', error)
        console.log('25. Тип ошибки:', typeof error)
        console.log('26. Сообщение ошибки:', error.message)
        
        isLoadingAccounts.value = false
        accountsError.value = 'Произошла ошибка при получении аккаунтов'
        
        console.log('27. Финальное состояние accountsError:', accountsError.value)
        console.log('=== КОНЕЦ CATCH ===')
    }
    
    console.log('=== КОНЕЦ getAccounts ===')
}
</script>

<template>
    <Head title="Агентские аккаунты ЯД - Администратор" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Агентские аккаунты Яндекс.Директ</h1>
        <p class="mt-2 text-gray-600">Управление агентскими аккаунтами и их настройками</p>
    </div>

    <!-- Отображение ошибок -->
    <div v-if="Object.keys(errors).length > 0" class="mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <ExclamationTriangleIcon class="w-5 h-5 text-red-400 mr-3 mt-0.5" />
                <div>
                    <h3 class="text-sm font-medium text-red-800">Произошли ошибки:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li v-for="(error, field) in errors" :key="field">
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Основная область -->
        <div class="space-y-6">
        <!-- Форма добавления аккаунта -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <PlusIcon class="w-6 h-6 text-blue-600 mr-3" />
                    <h2 class="text-xl font-semibold text-gray-900">Добавить агентский аккаунт</h2>
                </div>
            </div>
            
            <div class="p-6">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- Логин -->
                    <div>
                        <label for="yandex_login" class="block text-sm font-medium text-gray-700 mb-1">
                            Логин Яндекс.Директ
                        </label>
                        <input
                            id="yandex_login"
                            v-model="form.yandex_login"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="example@yandex.ru"
                        />
                    </div>

                    <!-- Пароль -->
                    <div>
                        <label for="yandex_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Пароль Яндекс.Директ
                        </label>
                        <input
                            id="yandex_password"
                            v-model="form.yandex_password"
                            type="password"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Введите пароль"
                        />
                    </div>

                    <!-- Тип агентского аккаунта -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            Тип агентского аккаунта
                        </label>
                        <select
                            id="type"
                            v-model="form.type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="own">Свои</option>
                            <option value="partner">Партнерские</option>
                        </select>
                    </div>

                    <!-- Кнопка получения OAuth токена -->
                    <div>
                        <button
                            type="button"
                            @click="getOAuthToken(form.yandex_login)"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                        >
                            <KeyIcon class="w-4 h-4 mr-2" />
                            Получить OAuth токен
                        </button>
                    </div>

                    <!-- Поле для ввода OAuth токена -->
                    <div>
                        <label for="oauth_token" class="block text-sm font-medium text-gray-700 mb-1">
                            OAuth токен
                        </label>
                        <input
                            id="oauth_token"
                            v-model="form.oauth_token"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Вставьте полученный токен сюда"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            После получения токена вставьте его в это поле
                        </p>
                    </div>

                    <!-- Кнопка добавления -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Добавить аккаунт
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Список агентских аккаунтов -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <BuildingOfficeIcon class="w-6 h-6 text-blue-600 mr-3" />
                    <h2 class="text-xl font-semibold text-gray-900">Агентские аккаунты</h2>
                </div>
            </div>
            
            <div class="p-6">
                <div v-if="agencyAccounts.length === 0" class="text-center py-12">
                    <BuildingOfficeIcon class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Нет агентских аккаунтов</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Агентские аккаунты Яндекс.Директ еще не добавлены.
                    </p>
                </div>
                
                <div v-else class="space-y-4">
                    <div v-for="account in agencyAccounts" :key="account.id" class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">{{ account.yandex_login }}</h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Агентский
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-blue-100 text-blue-800': account.type === 'own',
                                              'bg-orange-100 text-orange-800': account.type === 'partner'
                                          }">
                                        {{ account.type === 'own' ? 'Свои' : 'Партнерские' }}
                                    </span>
                                    <span v-if="account.is_active" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Активен
                                    </span>
                                    <span v-else class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Неактивен
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Пользователь:</span> {{ account.user?.name || 'Не привязан' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Тип:</span> {{ account.type === 'own' ? 'Свои' : 'Партнерские' }}
                                    </div>
                                    <div>
                                        <span class="font-medium">OAuth токен:</span> 
                                        <span :class="account.oauth_token ? 'text-green-600' : 'text-red-600'">
                                            {{ account.oauth_token ? 'Настроен' : 'Не настроен' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Дата добавления:</span> {{ new Date(account.created_at).toLocaleDateString('ru-RU') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <button 
                                    v-if="account.oauth_token"
                                    @click="getAccounts(account.id)"
                                    :disabled="isLoadingAccounts"
                                    class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    {{ isLoadingAccounts ? 'Загрузка...' : 'Получить аккаунты' }}
                                </button>
                                <span v-else class="text-xs text-gray-500">Нет OAuth токена</span>
                                <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                                    <EyeIcon class="w-4 h-4" />
                                </button>
                                <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                    <PencilIcon class="w-4 h-4" />
                                </button>
                                <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Информация о агентских аккаунтах -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <CogIcon class="w-6 h-6 text-blue-600" />
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900">Информация об агентских аккаунтах</h3>
                    <div class="mt-2 text-sm text-blue-800">
                        <p class="mb-2">
                            Агентские аккаунты позволяют управлять рекламными кампаниями от имени клиентов.
                        </p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Каждый агентский аккаунт должен иметь отдельный OAuth токен</li>
                            <li>Аккаунты могут быть активными или неактивными</li>
                            <li>Для работы с API необходимо настроить OAuth токен</li>
                            <li>Агентские аккаунты могут управлять кампаниями клиентов</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Динамическая область справа -->
        <div class="space-y-6">
            <!-- Область для отображения полученных аккаунтов -->
            <div v-if="accountsData || accountsError || isLoadingAccounts" class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <UserIcon class="w-6 h-6 text-green-600 mr-3" />
                        <h2 class="text-xl font-semibold text-gray-900">Полученные аккаунты</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Загрузка -->
                    <div v-if="isLoadingAccounts" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-sm text-gray-600">Загрузка аккаунтов...</p>
                    </div>

                    <!-- Ошибка -->
                    <div v-else-if="accountsError" class="text-center py-8">
                        <div class="text-red-600 mb-2">
                            <ExclamationTriangleIcon class="w-8 h-8 mx-auto" />
                        </div>
                        <p class="text-sm text-red-600">{{ accountsError }}</p>
                    </div>

                    <!-- Данные -->
                    <div v-else-if="accountsData" class="space-y-4">
                        <div v-if="accountsData.length === 0" class="text-center py-4">
                            <p class="text-sm text-gray-500">Аккаунты не найдены</p>
                        </div>
                        
                        <div v-else class="space-y-3">
                            <div v-for="(account, index) in accountsData" :key="index" class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">{{ account.Login || 'Без логина' }}</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div v-if="account.AccountId">
                                        <span class="font-medium">ID:</span> {{ account.AccountId }}
                                    </div>
                                    <div v-if="account.AccountName">
                                        <span class="font-medium">Название:</span> {{ account.AccountName }}
                                    </div>
                                    <div v-if="account.Status">
                                        <span class="font-medium">Статус:</span> 
                                        <span :class="{
                                            'text-green-600': account.Status === 'ACCEPTED',
                                            'text-yellow-600': account.Status === 'PENDING',
                                            'text-red-600': account.Status === 'REJECTED'
                                        }">
                                            {{ account.Status }}
                                        </span>
                                    </div>
                                    <div v-if="account.AccountType">
                                        <span class="font-medium">Тип:</span> {{ account.AccountType }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Сырые данные -->
                        <div class="mt-6">
                            <details class="border border-gray-200 rounded-lg">
                                <summary class="px-4 py-2 bg-gray-50 cursor-pointer text-sm font-medium text-gray-700">
                                    Показать сырые данные
                                </summary>
                                <div class="p-4 bg-gray-50">
                                    <pre class="text-xs text-gray-600 overflow-auto">{{ JSON.stringify(accountsData, null, 2) }}</pre>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 