<template>
    <nav class="grid w-full h-16 grid-cols-6 border-t shrink-0 border-t-slate-800 bg-slate-900 place-items-center">
        <Link v-for="item, index in menuItems.studio" :href="route(item.route)" :key="index" class="w-full p-0.5 pt-1.5 font-normal flex flex-col gap-1 h-full text-[10px] sm:text-xs md:text-sm text-center border-t-4" :class="route().current(item.active) ? 'border-t-orange-500 text-white' : 'border-t-transparent text-slate-400'">
            <i :class="item.icon + ' text-xs'" />
            {{ item.label }}
        </Link>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import menuItems from './menuItems.json';

</script>
