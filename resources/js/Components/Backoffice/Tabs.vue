<template>
    <ul class="flex gap-2 pb-4 mb-4 overflow-x-auto border-b border-gray-600 shrink-0">
        <li v-for="tabLink in props.tabLinks">
            <Link :href="tabLink.href" class="block px-4 py-1 text-base transition-colors bg-transparent border-2 rounded-full cursor-pointer hover:border-orange-500 hover:text-white focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 whitespace-nowrap" :class="tabLink.active ? 'border-orange-500 text-white font-medium' : 'border-gray-400 text-gray-400'">
                {{ tabLink.name }}
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
