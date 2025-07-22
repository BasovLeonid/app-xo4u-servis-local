<script setup>
import { Head } from '@inertiajs/vue3';
import AuthTabs from '../Components/AuthTabs.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Авторизация" />
    <AuthTabs />
</template>
