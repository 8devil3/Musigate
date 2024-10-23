<template>
    <div class="hidden lg:block lg:h-[640px] max-w-7xl mx-auto">
        <img v-if="!props.photos.length" src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full p-48 bg-slate-900">

        <div v-else class="flex w-full h-full gap-1">
            <div class="relative grid grid-cols-5 grid-rows-3 gap-1">
                <img :src="'/storage/' + props.photos[0].path" :alt="props.photos[0].filename" class="object-cover w-full h-full row-span-full" :class="props.photos.length === 1 ? 'col-span-full' : 'col-span-4'">

                <template v-if="props.photos.length >= 2">
                    <img v-for="photo in otherPhotos" :src="'/storage/' + photo.path" :alt="photo.filename" class="object-cover w-full h-full col-span-1">
                    <img v-for="i in 3 - otherPhotos.length" src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full col-span-1 p-12 bg-slate-900">
                </template>

                <button v-if="props.photos.length > 1" type="button" @click="openModalGallery = true" title="Galleria foto" class="absolute px-4 py-1 border border-orange-500 rounded-full shadow-md bottom-4 left-4 bg-black/80">
                    <i class="mr-2 text-lg fa-solid fa-images"></i>
                    Galleria
                    ({{ props.photos.length }})
                </button>
            </div>

        </div>
    </div>

    <div class="lg:hidden h-80 sm:h-[480px]">
        <Carosello :imgs="props.photos" />
    </div>

    <!-- lightbox -->
    <TransitionRoot as="template" :show="openModalGallery" >
        <Dialog @close="openModalGallery = false">
            <DialogPanel as="div">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm" />
                </TransitionChild>
    
                <div class="fixed inset-0 z-50 p-2">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel as="div" class="w-full h-full">
                            <button type="button" @click="openModalGallery = false" title="Chiudi" class="absolute z-50 flex items-center justify-center text-2xl text-white border border-orange-500 rounded-full size-10 right-4 top-4 bg-slate-950/50">
                                <i class="fa-solid fa-xmark" />
                            </button>

                            <!-- immagine corrente -->
                            <Carousel :items-to-show="1" :wrap-around="true" v-model="currentSlide" class="w-full h-5/6">
                                <Slide v-for="slide in props.photos" :key="slide.id" class="w-full h-full">
                                    <div class="w-full h-full carousel__item">
                                        <img :src="'/storage/' + slide.path" :alt="'Foto-' + slide.id" class="object-contain w-full h-full" />
                                    </div>
                                </Slide>

                                <template #addons>
                                    <Navigation />
                                </template>
                            </Carousel>
                            <!-- / -->

                            <!-- anteprime -->
                            <Carousel :items-to-show="6" :wrap-around="true" v-model="currentSlide" class="mt-2 h-1/6">
                                <Slide v-for="slide, index in props.photos" :key="slide.id" class="h-full w-full px-0.5">
                                    <div @click="slideTo(index)" class="w-full h-full cursor-pointer carousel__item">
                                        <img :src="'/storage/' + slide.path" :alt="'Foto-' + slide.id" class="object-cover object-center w-full h-full">
                                    </div>
                                </Slide>
                            </Carousel>
                            <!-- / -->
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </DialogPanel>
        </Dialog>
    </TransitionRoot>
    <!-- / -->
</template>

<script setup>
import { computed, ref } from "vue";
import Carosello from "@/Components/Carosello.vue";
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Carousel, Slide, Navigation } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';

const props = defineProps({
    photos: Object
});

const openModalGallery = ref(false);
const currentSlide = ref(0);

const otherPhotos = computed(()=>{
    if(props.photos.length){
        let photos = [...props.photos];    
    
        photos.splice(0,1);
        photos.splice(3)
    
        return photos;
    }

    else return [];
});

const slideTo = (index)=>{
    currentSlide.value = index;
};

</script>
