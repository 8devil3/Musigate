<template>
    <div class="relative hidden md:block md:h-[600px]">
        <img v-if="!props.imgs.length" src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full p-48 bg-gray-900">

        <img v-if="props.imgs.length === 1" :src="'/storage/' + props.imgs[0]" alt="Galleria" class="object-cover w-full h-full">

        <div v-if="props.imgs.length > 1 && props.imgs.length < 5" class="flex object-cover h-full gap-2 bg-gray-900">
            <img :src="'/storage/' + props.imgs[0]" alt="Galleria" class="w-4/5 h-full">
            <div class="grid w-1/5 h-full grid-cols-1 grid-rows-3 gap-2">
                <img v-for="img in props.imgs.slice(1, 7)" :src="'/storage/' + img" alt="" class="object-cover w-full h-full">
            </div>
        </div>

        <div v-if="props.imgs.length >= 5" class="flex h-full gap-2 bg-gray-900">
            <img :src="'/storage/' + props.imgs[0]" alt="Galleria" class="object-cover w-3/5 h-full">
            <div class="grid w-2/5 h-full grid-cols-2 grid-rows-3 gap-2">
                <img v-for="img in props.imgs.slice(1, 7)" :src="'/storage/' + img" alt="" class="object-cover w-full h-full">
            </div>
        </div>

        <button v-if="props.imgs.length" type="button" @click="openModalGallery = true" title="Galleria foto" class="absolute px-4 py-1 border border-orange-500 rounded-full shadow-md bottom-4 left-8 bg-black/50">
            <i class="mr-2 text-lg fa-solid fa-images"></i>
            {{ props.imgs.length }}
        </button>
    </div>

    <Carosello :imgs="props.imgs" class="md:hidden h-80" />

    <Modal :isOpen="openModalGallery" @close="openModalGallery = false" maxWidth="w-full" maxHeight="h-full">
        <template #title>
            Galleria
        </template>

        <template #description>
            <Carosello :imgs="props.imgs" objectImg="object-contain" />
        </template>
    </Modal>
</template>

<script setup>
import { ref } from "vue";
import Modal from '@/Components/Modal.vue';
import Carosello from '@/Components/Frontoffice/Carosello.vue';

const props = defineProps({
    imgs: Array
});

const openModalGallery = ref(false);

</script>
