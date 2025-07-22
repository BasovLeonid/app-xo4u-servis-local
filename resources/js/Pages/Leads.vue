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
    <Head title="Лиды - Панель управления" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Лиды</h1>
        <p class="mt-2 text-gray-600">Управление лидами и потенциальными клиентами</p>
    </div>

    <!-- Пустой контент -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Страница в разработке</h3>
            <p class="mt-2 text-sm text-gray-500">
                Функционал управления лидами будет добавлен позже.
            </p>
        </div>
    </div>
</template> 