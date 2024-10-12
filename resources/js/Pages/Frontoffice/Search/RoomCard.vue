<template>
    <article class="flex flex-col overflow-hidden border border-slate-500 min-h-40 bg-slate-900/75 rounded-xl">
        <div class="relative h-56">
            <!-- badge sconto -->
            <div v-if="props.room.has_discounted_fixed_price || props.room.min_discounted_price" class="absolute z-50 w-40 py-1.5 text-xs font-medium text-center text-white origin-top rotate-45 translate-x-1/2  bg-orange-900/80 backdrop-blur-sm border-orange-500 shadow-sm border-y-2 top-2 right-2">
                <!-- <i class="-rotate-45 fa-solid fa-tag" /> -->
                <div>
                    Tariffa<br>
                    scontata
                </div>
            </div>
            <!-- / -->

            <Carosello :imgs="props.room.photos" class="h-full" />

            <div class="absolute flex items-center justify-center text-center border-2 border-orange-500 rounded-full shadow-sm size-12 bottom-2 left-3 bg-slate-900 shrink-0">
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
                    {{ props.room.max_capacity }} max
                </li>

                <li title="Capienza massima" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-hourglass-half" />
                    {{ props.room.min_booking === 1 ? props.room.min_booking + ' min. ora' : props.room.min_booking + ' min. ore' }}
                </li>
            </ul>

            <!-- nome -->
            <div class="space-y-1">
                <div class="text-xs font-medium uppercase text-slate-400">{{ props.room.studio.category }} studio</div>
                <h2 class="text-lg text-white md:text-xl font-lemon">
                    {{ props.room.studio.name }}
                </h2>
                <h3 class="text-sm text-slate-300">
                    {{ props.room.name }}
                </h3>
            </div>
            <!-- / -->

            <div class="flex items-center gap-6 border-t border-orange-500">
                <!-- location e tariffa minima -->
                <address class="py-3 font-sans text-sm not-italic grow">
                    <div>
                        <i class="mr-1 text-sm text-orange-500 fa-solid fa-location-dot" />
                        {{ props.room.studio.location.city }}
                    </div>
                    <div class="ml-[18px]">
                        {{ props.room.studio.location.province }}
                    </div>
                </address>
                <!-- / -->
    
                <div v-if="props.room.fixed_price || props.room.has_discounted_fixed_price || props.room.min_price || props.room.min_discounted_price" title="Tariffa oraria" class="text-sm font-lemon shrink-0">
                    <template v-if="props.room.min_discounted_price">
                        <div class="text-xs line-through text-slate-400">
                            {{ props.room.min_price }} €/h
                        </div>
                        <div>
                            {{ props.room.min_discounted_price }} €/h
                        </div>
                    </template>
    
                    <div v-else-if="props.room.min_price">
                        {{ props.room.min_price }} €/h
                    </div>
    
                    <template v-else-if="props.room.discounted_fixed_price">
                        <div class="text-xs line-through text-slate-400">
                            {{ props.room.fixed_price }} €/h
                        </div>
                        <div>
                            {{ props.room.discounted_fixed_price }} €/h
                        </div>
                    </template>
    
                    <div v-else>
                        {{ props.room.discounted_fixed_price }} €/h
                    </div>
                </div>
            </div>
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
