<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { 
    UserIcon,
    CogIcon,
    ArrowRightOnRectangleIcon,
    PlusIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const isProfileMenuOpen = ref(false)

const toggleProfileMenu = () => {
    isProfileMenuOpen.value = !isProfileMenuOpen.value
}

const handleLogout = () => {
    router.post('/logout')
}

const handleTopUp = () => {
    router.visit('/balance')
}
</script>

<template>
    <!-- Верхняя панель -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between h-16 px-6">
            <div class="flex items-center">
                <h1 class="text-xl font-semibold text-gray-900">Панель управления</h1>
            </div>
            
            <!-- Информация о пользователе и действия -->
            <div class="flex items-center space-x-4">
                <!-- Баланс -->
                <div class="flex items-center space-x-3 bg-blue-50 px-4 py-2 rounded-lg">
                    <div class="text-sm">
                        <span class="text-gray-600">Баланс:</span>
                        <span class="ml-1 font-semibold text-blue-600">{{ user.balance || 0 }} ₽</span>
                    </div>
                    <button
                        @click="handleTopUp"
                        class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors"
                    >
                        <PlusIcon class="w-3 h-3 mr-1" />
                        Пополнить
                    </button>
                </div>

                <!-- Профиль пользователя -->
                <div class="relative">
                    <button
                        @click="toggleProfileMenu"
                        class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-100 transition-colors"
                    >
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <UserIcon class="w-5 h-5 text-blue-600" />
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium text-gray-700">{{ user.name }}</div>
                            <div class="text-xs text-gray-500">ID: {{ user.id }}</div>
                        </div>
                    </button>

                    <!-- Выпадающее меню профиля -->
                    <div
                        v-if="isProfileMenuOpen"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                    >
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                            <div class="text-xs text-gray-500">{{ user.email }}</div>
                        </div>
                        
                        <a
                            href="/profile"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <CogIcon class="w-4 h-4 mr-3 text-gray-400" />
                            Настройки
                        </a>
                        
                        <button
                            @click="handleLogout"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                        >
                            <ArrowRightOnRectangleIcon class="w-4 h-4 mr-3 text-gray-400" />
                            Выход
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> 