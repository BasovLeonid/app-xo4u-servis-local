<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { 
    UserIcon,
    EnvelopeIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-6">
            <div class="flex items-center">
                <UserIcon class="w-6 h-6 text-blue-600 mr-3" />
                <h2 class="text-xl font-semibold text-gray-900">
                    Информация профиля
                </h2>
            </div>
            <p class="mt-2 text-sm text-gray-600">
                Обновите личную информацию и адрес электронной почты
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <!-- Имя -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Имя
                </label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                    {{ form.errors.name }}
                </p>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': form.errors.email }"
                />
                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                    {{ form.errors.email }}
                </p>
            </div>

            <!-- Верификация email -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <ExclamationTriangleIcon class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" />
                    <div>
                        <p class="text-sm text-yellow-800">
                            Ваш email не подтвержден.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="font-medium underline hover:text-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                            >
                                Нажмите здесь, чтобы отправить письмо подтверждения.
                            </Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Успешная отправка верификации -->
            <div
                v-show="status === 'verification-link-sent'"
                class="bg-green-50 border border-green-200 rounded-md p-4"
            >
                <div class="flex">
                    <CheckCircleIcon class="w-5 h-5 text-green-400 mr-3 mt-0.5" />
                    <p class="text-sm font-medium text-green-800">
                        Новое письмо подтверждения отправлено на ваш email.
                    </p>
                </div>
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
                        Сохранено!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
