<script setup>
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'
import TopBar from '@/Components/TopBar.vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)

const isSidebarOpen = ref(true)

const mainAreaClasses = computed(() => [
    'flex-1 flex flex-col transition-all duration-300 ease-in-out',
    isSidebarOpen.value ? 'ml-80' : 'ml-0'
])
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Сайдбар -->
        <Sidebar :user="user" @toggle="isSidebarOpen = !isSidebarOpen" />
        
        <!-- Основная область -->
        <div :class="mainAreaClasses">
            <!-- TopBar -->
            <TopBar :user="user" />
            
            <!-- Динамический контент страницы -->
            <div class="flex-1 p-6">
                <slot />
            </div>
        </div>
    </div>
</template> 