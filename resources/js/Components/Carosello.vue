<template>
    <Carousel v-if="props.imgs" :items-to-show="1" class="w-full">
        <slide v-for="img, id in props.imgs" :key="id" class="w-full h-full">
            <div class="w-full h-full">
                <img :src="'/storage/' + img.path" :alt="'Foto-' + id" class="w-full h-full" :class="objectImg">
            </div>
        </slide>
        <template #addons>
            <Navigation />
        </template>
    </Carousel>

    <div v-else class="flex items-center justify-center w-full h-80">
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
    objectImg: {
        type: String,
        default: 'object-cover'
    }
});

</script>
