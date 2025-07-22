<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
    auth: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head title="Аккаунты - Панель управления" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Аккаунты</h1>
        <p class="mt-2 text-gray-600">Управление рекламными аккаунтами</p>
    </div>

    <!-- Пустой контент -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Страница в разработке</h3>
            <p class="mt-2 text-sm text-gray-500">
                Функционал управления аккаунтами будет добавлен позже.
            </p>
        </div>
    </div>
</template> 