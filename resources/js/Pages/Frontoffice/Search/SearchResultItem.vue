<template>
    <article class="overflow-hidden border border-gray-500 min-h-40 bg-gray-900/75 md:flex rounded-xl">
        <div class="w-full md:max-w-80">
            <Carosello :imgs="imgs" class="md:h-full" />
        </div>
        
        <div class="flex flex-col gap-6 p-4 md:px-6 md:grow">
            <!-- nome e logo -->
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-16 h-16 text-center bg-gray-900 border-2 border-orange-500 rounded-full shrink-0">
                    <img v-if="props.studio.logo" :src="'/storage/' + props.studio.logo" :alt="props.studio.name" class="object-contain w-full h-full rounded-full">
                    <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.studio.name" class="object-contain w-1/2 aspect-square">
                </div>
                <div>
                    <div class="text-sm text-gray-400 uppercase font-lemon">{{ props.studio.category }} studio</div>
                    <Link :href="route('studio.show', props.studio.id)" class="text-2xl font-lemon">
                        {{ props.studio.name }}
                    </Link>
                </div>
            </div>
            <!-- / -->

            <!-- servizi -->
            <div v-if="props.studio.services.length" class="space-y-2">
                <h4>Servizi</h4>
                <div class="flex flex-wrap items-center gap-1">
                    <template v-for="service, id in props.studio.services">
                        <Badge v-if="id < 4" :text="service.name" color="yellow" />
                    </template>
                    <div v-if="props.studio.services.length > 4"> ... e altro</div>
                </div>
            </div>
            <!-- / -->

            <!-- comfort -->
            <div v-if="props.studio.comforts.length" class="space-y-2">
                <h4>Comfort</h4>
                <div class="flex flex-wrap items-center gap-1">
                    <template v-for="comfort, id in props.studio.comforts">
                        <Badge v-if="id < 4" :text="comfort.name" color="indigo" />
                    </template>
                    <div v-if="props.studio.comforts.length > 4"> ... e altro</div>
                </div>
            </div>
            <!-- / -->

            <!-- location e tariffa minima -->
            <div class="flex flex-wrap items-end justify-between mt-auto gap-x-10 gap-y-4">
                <div class="font-sans text-sm">
                    <div>
                        <i class="mr-1 text-sm text-orange-500 fa-solid fa-location-dot"></i>
                        {{ props.studio.location.city }}
                    </div>
                    <div class="ml-[18px]">
                        {{ props.studio.location.province }}
                    </div>
                </div>
                <div v-if="props.studio.min_price && props.studio.min_price > 0" class="lg:text-right whitespace-nowrap">
                    <div class="font-sans text-xs">a partire da</div>
                    <div class="text-base font-lemon ml-0.5">{{ props.studio.min_price }} €/h</div>
                </div>
            </div>
            <!-- / -->
        </div>
    </article>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Carosello from '@/Components/Carosello.vue';
import Badge from '@/Components/Frontoffice/Badge.vue';

const props = defineProps({
   studio: Object,
});

const imgs = computed(()=>{
    let imgs = props.studio.photos.sort(photo => photo.is_featured ? -1 : 1).map(photo => photo.path).flat();

    if(imgs.length){
        return imgs;
    } else {
        return [];
    }
});

</script>
