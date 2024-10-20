<template>
    <article class="flex flex-col overflow-hidden border border-slate-500 min-h-40 bg-slate-900/75 rounded-xl">
        <div class="relative h-56">
            <img v-if="props.bundle.cover_path" :src="props.bundle.cover_path" :alt="props.bundle.name" class="object-cover w-full h-full" />
            <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full p-14 bg-slate-800/40">

            <div class="absolute flex items-center justify-center text-center border-2 border-orange-500 rounded-full shadow-sm size-12 bottom-2 left-3 bg-slate-900 shrink-0">
                <img v-if="props.studio.logo" :src="'/storage/' + props.studio.logo" :alt="props.studio.name" class="object-contain w-full h-full rounded-full">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.studio.name" class="object-contain w-1/2 aspect-square">
            </div>
        </div>

        <Link :href="route('studio.show', props.studio.id)" class="flex flex-col gap-6 px-4 py-3 grow">
            <ul v-if="props.bundle.duration || props.bundle.fixed_price || props.bundle.has_discounted_fixed_price || props.bundle.min_price || props.bundle.min_discounted_price" class="flex gap-6 font-normal list-none border-b list-image-none border-b-orange-500">
                <li v-if="props.bundle.duration" title="Durata" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-hourglass-half" />
                    {{ props.bundle.duration === 1 ? props.bundle.duration + ' ora' : props.bundle.duration + ' ore' }}
                </li>

                <li v-if="props.bundle.fixed_price || props.bundle.has_discounted_fixed_price || props.bundle.min_price || props.bundle.min_discounted_price" title="Tariffa minima" class="flex items-center gap-2 text-xs leading-none">
                    <i class="text-orange-500 fa-solid fa-euro" />
                    <template v-if="props.bundle.min_discounted_price">
                        <span class="line-through text-slate-400">
                            {{ props.bundle.min_price }} €/h
                        </span>
                        <span>
                            {{ props.bundle.min_discounted_price }} €/h
                        </span>
                    </template>

                    <span v-else-if="props.bundle.min_price">
                        {{ props.bundle.min_price }} €/h
                    </span>

                    <template v-else-if="props.bundle.discounted_fixed_price">
                        <span class="line-through text-slate-400">
                            {{ props.bundle.fixed_price }} €/h
                        </span>
                        <span>
                            {{ props.bundle.discounted_fixed_price }} €/h
                        </span>
                    </template>

                    <span v-else>
                        {{ props.bundle.discounted_fixed_price }} €/h
                    </span>
                </li>
            </ul>

            <!-- nome -->
            <div class="space-y-1">
                <div class="text-xs font-medium uppercase text-slate-400">{{ props.studio.category }} studio</div>
                <h2 class="text-lg text-white md:text-xl font-lemon">
                    {{ props.studio.name }}
                </h2>
                <h3 class="text-sm text-slate-300">
                    {{ props.bundle.name }}
                </h3>
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

const props = defineProps({
    bundle: Object,
    studio: Object,
});

</script>
