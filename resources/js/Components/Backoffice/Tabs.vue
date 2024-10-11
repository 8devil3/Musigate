<template>
    <ul class="flex gap-2 pb-4 mb-4 overflow-x-auto border-b border-slate-600 shrink-0">
        <li v-for="tabLink in props.tabLinks">
            <Link :href="route().current(tabLink.route, tabLink.params) ? null : route(tabLink.route, tabLink.params)" class="block px-4 py-1 text-base transition-colors border-2 rounded-full cursor-pointer hover:border-orange-500 hover:text-white focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 whitespace-nowrap" :class="route().current(tabLink.route, tabLink.params) ? 'border-orange-500 bg-orange-500/10 text-white font-medium' : 'bg-transparent border-slate-400 text-slate-400'">
                {{ tabLink.label }}
            </Link>
        </li>
    </ul>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tabLinks: Object
});

</script>
