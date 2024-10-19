<template>
    <ul class="flex w-full mb-4 overflow-x-scroll">
        <template v-for="tabLink, index in props.tabLinks">
            <li v-if="index" class="w-4 border-b-[3px] shrink-0 border-slate-700" />

            <li v-if="!route().current(tabLink.route, tabLink.params)" class="border-b-[3px] px-2 shrink-0 border-slate-700">
                <Link :href="route(tabLink.route, tabLink.params)" class="pb-2 text-sm font-normal transition-colors md:text-base last-of-type:mr-0 cursor-point hover:text-white focus:outline-none text-slate-500 focus:ring-0 focus-visible:outline-none focus-visible:ring-0 whitespace-nowrap">
                    {{ tabLink.label }}
                </Link>
            </li>

            <li v-else class="pb-2 px-2 text-sm md:text-base font-normal text-white transition-colors border-b-[3px] border-orange-500 shrink-0 last-of-type:mr-0 whitespace-nowrap">
                {{ tabLink.label }}
            </li>
        </template>

        <li class="border-b-[3px] border-slate-700 grow" />
    </ul>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tabLinks: Object
});

</script>
