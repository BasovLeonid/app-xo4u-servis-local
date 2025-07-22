<script setup>
import { Head } from '@inertiajs/vue3';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    auth: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Настройки профиля - Панель управления" />
    
    <!-- Заголовок страницы -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Настройки профиля</h1>
        <p class="mt-2 text-gray-600">Управление личной информацией и настройками аккаунта</p>
    </div>

    <!-- Секции настроек -->
    <div class="space-y-6">
        <!-- Информация профиля -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>
        </div>

        <!-- Смена пароля -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <UpdatePasswordForm />
            </div>
        </div>

        <!-- Удаление аккаунта -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <DeleteUserForm />
            </div>
        </div>
    </div>
</template>
