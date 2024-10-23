<template>
    <header class="z-20 hidden h-16 pr-4 bg-slate-900 lg:flex lg:items-center lg:justify-between shrink-0 lg:pr-8">
        <div class="h-full">
            <Link :href="route('dashboard')" class="w-64 h-14 mt-1.5 flex items-center justify-center">
                <img src="/img/logo/logo_horizontal.svg" alt="Musigate logo" class="h-6">
            </Link>
            <hr class="hidden shrink-0 lg:block h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />
        </div>

        <UserMenu />
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';

</script>
