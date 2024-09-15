<template>
    <header class="z-20 hidden h-16 px-4 lg:flex lg:items-center lg:justify-between lg:bg-gray-900 bg-gray-950 shrink-0 lg:px-8">
        <Link :href="route('dashboard')" class="block pl-5">
            <img src="/img/logo/logo_horizontal.svg" alt="Musigate logo" class="h-6">
        </Link>
        
        <UserMenu />
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';

</script>
