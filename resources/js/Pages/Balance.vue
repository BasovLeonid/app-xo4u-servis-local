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
    <Head title="Пополнение баланса - Панель управления" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Пополнение баланса</h1>
        <p class="mt-2 text-gray-600">Выберите способ пополнения и введите сумму</p>
    </div>

    <!-- Пустой контент -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-12 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Страница в разработке</h3>
            <p class="mt-2 text-sm text-gray-500">
                Функционал пополнения баланса будет добавлен позже.
            </p>
        </div>
    </div>
</template> 