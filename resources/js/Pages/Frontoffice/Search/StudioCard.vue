<template>
    <article class="flex flex-col overflow-hidden border border-slate-500 min-h-40 bg-slate-900/75 rounded-xl">
        <div class="relative h-56">
            <Carosello :imgs="props.studio.photos" class="h-full" />

            <div class="absolute flex items-center justify-center text-center border-2 border-orange-500 rounded-full shadow-sm size-12 bottom-2 left-3 bg-slate-900 shrink-0">
                <img v-if="props.studio.logo" :src="'/storage/' + props.studio.logo" :alt="props.studio.name" class="object-contain w-full h-full rounded-full">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.studio.name" class="object-contain w-1/2 aspect-square">
            </div>
        </div>

        <Link :href="route('studio.show', props.studio.slug)" class="flex flex-col gap-4 p-4 grow">
            <!-- nome -->
            <div>
                <div class="text-xs font-medium uppercase text-slate-400">{{ props.studio.category }} studio</div>
                <h2 class="text-base text-white md:text-lg font-lemon">
                    {{ props.studio.name }}
                </h2>
            </div>
            <!-- / -->

            <div class="flex items-end gap-4 pt-3 mt-auto border-t border-orange-500">
                <!-- location e tariffa minima -->
                <address class="font-sans text-sm not-italic grow">
                    <div>
                        <i class="mr-1 text-sm text-orange-500 fa-solid fa-location-dot" />
                        {{ props.studio.location.city }}
                    </div>
                    <div class="ml-[18px]">
                        {{ props.studio.location.province }}
                    </div>
                </address>
                <!-- / -->
            </div>
        </Link>
    </article>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Carosello from '@/Components/Carosello.vue';

const props = defineProps({
    studio: Object,
});

</script>
