<template>
    <Carousel v-if="props.imgs.length" :items-to-show="1" wrap-around class="w-full h-full">
        <Slide v-for="img in props.imgs" :key="img.id" class="w-full h-full">
            <div class="w-full h-full">
                <img :src="'/storage/' + img.path" :alt="'Foto-' + img.id" class="object-cover w-full h-full">
            </div>
        </Slide>
        <template #addons>
            <Navigation />
        </template>
    </Carousel>

    <div v-else class="flex items-center justify-center w-full h-full bg-slate-800/40">
        <img src="/img/logo/logo_placeholder.svg" class="object-contain h-1/2 aspect-square">
    </div>
</template>

<script setup>
import { Carousel, Slide, Navigation } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

const props = defineProps({
    imgs: {
        type: Object,
        default: null
    },
});

</script>
