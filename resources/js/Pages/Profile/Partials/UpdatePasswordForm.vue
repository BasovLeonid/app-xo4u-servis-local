<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
    KeyIcon,
    EyeIcon,
    EyeSlashIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <div class="flex items-center">
                <KeyIcon class="w-6 h-6 text-blue-600 mr-3" />
                <h2 class="text-xl font-semibold text-gray-900">
                    Смена пароля
                </h2>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                Убедитесь, что ваш аккаунт использует надежный пароль для безопасности
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <!-- Текущий пароль -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Текущий пароль
                </label>
                <div class="relative">
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        autocomplete="current-password"
                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': form.errors.current_password }"
                    />
                    <button
                        type="button"
                        @click="showCurrentPassword = !showCurrentPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <EyeIcon v-if="!showCurrentPassword" class="h-5 w-5 text-gray-400" />
                        <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                    </button>
                </div>
                <p v-if="form.errors.current_password" class="mt-2 text-sm text-red-600">
                    {{ form.errors.current_password }}
                </p>
            </div>

            <!-- Новый пароль -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Новый пароль
                </label>
                <div class="relative">
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showNewPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': form.errors.password }"
                    />
                    <button
                        type="button"
                        @click="showNewPassword = !showNewPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <EyeIcon v-if="!showNewPassword" class="h-5 w-5 text-gray-400" />
                        <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                    </button>
                </div>
                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                    {{ form.errors.password }}
                </p>
            </div>

            <!-- Подтверждение пароля -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Подтверждение пароля
                </label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': form.errors.password_confirmation }"
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <EyeIcon v-if="!showConfirmPassword" class="h-5 w-5 text-gray-400" />
                        <EyeSlashIcon v-else class="h-5 w-5 text-gray-400" />
                    </button>
                </div>
                <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <!-- Кнопки -->
            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    <CheckCircleIcon v-if="!form.processing" class="w-4 h-4 mr-2" />
                    <svg v-else class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                </button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 font-medium"
                    >
                        Пароль обновлен!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
