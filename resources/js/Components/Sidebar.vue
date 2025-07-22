<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { 
    HomeIcon,
    UserIcon,
    ChartBarIcon,
    DocumentTextIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const page = usePage()
const isSidebarOpen = ref(true)

// Определяем текущий путь
const currentPath = computed(() => page.url)

// Функция для проверки активности пункта меню
const isActive = (href) => {
    if (href === '/dashboard') {
        return currentPath.value === '/dashboard'
    }
    return currentPath.value.startsWith(href)
}

const menuItems = computed(() => [
    {
        name: 'Моя реклама',
        icon: HomeIcon,
        href: '/dashboard',
        current: isActive('/dashboard')
    },
    {
        name: 'Аккаунты',
        icon: UserIcon,
        href: '/accounts',
        current: isActive('/accounts')
    },
    {
        name: 'Статистика',
        icon: ChartBarIcon,
        href: '/statistics',
        current: isActive('/statistics')
    },
    {
        name: 'Лиды',
        icon: DocumentTextIcon,
        href: '/leads',
        current: isActive('/leads')
    }
])

const adminMenuItems = computed(() => [
    {
        name: 'Яндекс Директ',
        icon: ChartBarIcon,
        href: '/admin/yandex-direct',
        current: isActive('/admin/yandex-direct'),
        subItems: [
            {
                name: 'Агентские аккаунты ЯД',
                icon: UserIcon,
                href: '/admin/yandex-direct/agency-accounts',
                current: isActive('/admin/yandex-direct/agency-accounts')
            },
            {
                name: 'Клиентские аккаунты ЯД',
                icon: UserIcon,
                href: '/admin/yandex-direct/client-accounts',
                current: isActive('/admin/yandex-direct/client-accounts')
            },
            {
                name: 'Шаблоны РК ЯД',
                icon: DocumentTextIcon,
                href: '/admin/yandex-direct/templates',
                current: isActive('/admin/yandex-direct/templates')
            }
        ]
    }
])

const emit = defineEmits(['toggle'])

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
    emit('toggle')
}
</script>

<template>
    <!-- Сайдбар -->
    <div 
        :class="[
            'fixed inset-y-0 left-0 z-50 w-80 bg-white shadow-lg transform transition-transform duration-300 ease-in-out',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
            <!-- Заголовок сайдбара -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">XO</span>
                    </div>
                    <span class="ml-3 text-lg font-semibold text-gray-900">Панель управления</span>
                </div>
                <button
                    @click="toggleSidebar"
                    class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>



            <!-- Навигационное меню -->
            <nav class="flex-1 px-4 py-4 space-y-1">
                <template v-for="item in menuItems" :key="item.name">
                    <a
                        :href="item.href"
                        :class="[
                            'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
                            item.current
                                ? 'bg-blue-100 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                'mr-3 h-5 w-5',
                                item.current ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'
                            ]"
                        />
                        {{ item.name }}
                    </a>
                </template>
            </nav>

            <!-- Раздел АДМИНИСТРАТОР -->
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="mb-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        АДМИНИСТРАТОР
                    </h3>
                </div>
                <nav class="space-y-1">
                    <template v-for="item in adminMenuItems" :key="item.name">
                        <a
                            :href="item.href"
                            :class="[
                                'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
                                item.current
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                            ]"
                        >
                            <component
                                :is="item.icon"
                                :class="[
                                    'mr-3 h-5 w-5',
                                    item.current ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'
                                ]"
                            />
                            {{ item.name }}
                        </a>
                        <!-- Подразделы -->
                        <div v-if="item.subItems" class="ml-6 mt-1 space-y-1">
                            <template v-for="subItem in item.subItems" :key="subItem.name">
                                <a
                                    :href="subItem.href"
                                    :class="[
                                        'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
                                        subItem.current
                                            ? 'bg-blue-50 text-blue-600'
                                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'
                                    ]"
                                >
                                    <component
                                        :is="subItem.icon"
                                        :class="[
                                            'mr-3 h-4 w-4',
                                            subItem.current ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-500'
                                        ]"
                                    />
                                    {{ subItem.name }}
                                </a>
                            </template>
                        </div>
                    </template>
                </nav>
            </div>


        </div>
</template> 