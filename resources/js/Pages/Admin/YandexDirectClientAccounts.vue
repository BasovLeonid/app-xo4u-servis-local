<script setup>
import { Head } from '@inertiajs/vue3'
import { 
    UserIcon
} from '@heroicons/vue/24/outline'

defineProps({
    auth: {
        type: Object,
        required: true
    },
    clientAccounts: {
        type: Array,
        default: () => []
    }
})
</script>

<template>
    <Head title="Клиентские аккаунты ЯД - Администратор" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Клиентские аккаунты Яндекс.Директ</h1>
        <p class="mt-2 text-gray-600">Управление клиентскими аккаунтами и их настройками</p>
    </div>

    <div class="max-w-4xl space-y-6">
        <!-- Пустой контент -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <UserIcon class="w-6 h-6 text-blue-600 mr-3" />
                    <h2 class="text-xl font-semibold text-gray-900">Клиентские аккаунты</h2>
                </div>
            </div>
            
            <div class="p-12 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Страница в разработке</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Функционал управления клиентскими аккаунтами будет добавлен позже.
                </p>
            </div>
        </div>
    </div>
</template> 