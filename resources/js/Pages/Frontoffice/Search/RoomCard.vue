<template>
    <article class="flex flex-col overflow-hidden border border-slate-500 min-h-40 bg-slate-900/75 rounded-xl">
        <div class="relative h-56">
            <Carosello :imgs="props.room.photos" class="h-full" />

            <div class="absolute flex items-center justify-center w-12 h-12 text-center border-2 border-orange-500 rounded-full shadow-sm bottom-2 left-3 bg-slate-900 shrink-0">
                <img v-if="props.room.studio.logo" :src="'/storage/' + props.room.studio.logo" :alt="props.room.studio.name" class="object-contain w-full h-full rounded-full">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.room.studio.name" class="object-contain w-1/2 aspect-square">
            </div>
        </div>

        <Link :href="route('studio.show', props.room.studio.id)" class="block px-4 space-y-6 grow">
            <ul class="flex gap-6 py-3 font-normal list-none border-b list-image-none border-b-orange-500">
                <li title="Area" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-ruler-combined" />
                    {{ props.room.area }} mq
                </li>

                <li title="Capienza massima" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-users" />
                    {{ props.room.max_capacity }}
                </li>

                <li title="Tariffa oraria" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-euro" />
                    {{ props.room.price }} €/h
                </li>
            </ul>

            <!-- nome -->
            <div class="space-y-1">
                <div class="text-xs font-medium uppercase text-slate-400">{{ props.room.studio.category }} studio</div>
                <h2 class="text-lg text-white font-lemon">
                    {{ props.room.studio.name }}
                </h2>
                <h3 class="text-sm text-slate-300">
                    {{ props.room.name }}
                </h3>
            </div>
            <!-- / -->

            <!-- location e tariffa minima -->
            <address class="py-3 font-sans text-sm not-italic border-t border-orange-500">
                <div>
                    <i class="mr-1 text-sm text-orange-500 fa-solid fa-location-dot" />
                    {{ props.room.studio.location.city }}
                </div>
                <div class="ml-[18px]">
                    {{ props.room.studio.location.province }}
                </div>
            </address>
            <!-- / -->
        </Link>
    </article>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Carosello from '@/Components/Carosello.vue';
import Badge from '@/Components/Frontoffice/Badge.vue';

const props = defineProps({
    room: Object,
});

</script>
