<template>
    <FrontofficeLayout title="Cerca Studi">
        <!-- search bar desktop-->
        <form @submit.prevent="submit()" class="sticky top-0 z-50 flex-wrap items-end hidden px-6 py-4 border-b bg-slate-950/60 border-slate-700 backdrop-blur-md md:flex gap-x-2 gap-y-4">
            <SearchLocation v-model="form.location" @update:model-value="submit()" label="Location" class="grow" />
            <RangeSlider v-model="form.radius" @change="submit()" :min="50" :max="500" :step="10" label="Raggio" unit="km" :disabled="!form.location" class="self-start px-2" />
            <Input type="dateTime-local" v-model="form.start" @input="submit()" :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" placeholder="Inizio" label="Data e ora d'inizio" class="grow max-w-48" />
            <NumberInput v-model="form.duration" @input="submit()" @change="submit()" :min="1" :max="24" label="Durata" unit="ore" />
            <NumberInput v-model="form.guests" @input="submit()" @change="submit()" :min="1" :max="99" label="Persone" />

            <div class="flex items-end gap-2 grow">
                <Input type="search" v-model="form.name" @input="submit()" placeholder="Nome studio" label="Nome Studio" class="grow" />
                <Input type="search" v-model="form.equip" @input="submit()" placeholder="Equipaggiamento" label="Cerca equipaggiamento" class="grow" />
                <Button v-if="!showMap" @click="showMap = true" title="Mostra mappa" icon="fa-solid fa-map-location-dot" :disabled="form.processing" />
                <Button v-else @click="showMap = false" title="Nascondi mappa" icon="fa-solid fa-map-location-dot" :disabled="form.processing" color="green" />
                <Button @click="reset()" icon="fa-solid fa-arrow-rotate-left" color="slate" title="Reset filtri" :disabled="form.processing" />
            </div>
        </form>
        <!-- / -->

        <!-- search bar mobile -->
        <div class="sticky top-0 z-50 flex w-full gap-2 p-4 border-b md:hidden bg-slate-950/60 border-slate-700 backdrop-blur-md">
            <Button @click="isOpenModalFilters = true" text="Filtra" icon="fa-solid fa-sliders" :disabled="form.processing" class="grow" />
            <Button v-if="!showMap" @click="showMap = true; isOpenModalFilters = false" title="Mostra mappa" icon="fa-solid fa-map-location-dot" :disabled="form.processing" class="shrink-0" />
            <Button v-else @click="showMap = false; isOpenModalFilters = false" title="Nascondi mappa" icon="fa-solid fa-map-location-dot" color="green" :disabled="form.processing" class="shrink-0" />
        </div>
        <!-- / -->

        <!-- mappa -->
        <div v-if="!form.processing" v-show="showMap" class="relative h-96">
            <GoogleMaps
                :studios="studios"
                :lat="props.request?.location?.lat"
                :lon="props.request?.location?.lon"
                class="h-full"
            />
        </div>
        <!-- / -->

        <!-- risultati ricerca -->
        <div v-if="!form.processing" class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 md:gap-6 md:p-6">
            <RoomCard v-if="props.rooms.data.length" v-for="room in props.rooms.data" :room="room"/>

            <p v-else class="p-12 text-xl font-semibold text-center md:text-2xl col-span-full text-slate-500">
                Nessun risultato :(
            </p>
        </div>

        <div v-else class="flex items-center justify-center p-12 mt-12">
            <Spinner class="w-12 h-12 orange" />
        </div>
        <!-- / -->

        <!-- paginazione -->
        <Pagination v-if="!form.processing" :data="props.rooms.data" :links="props.rooms.links" />
        <!-- / -->

        <!-- to top -->
        <button type="button" @click="toTop()" class="fixed flex items-center justify-center w-10 h-10 text-lg leading-none transition-opacity bg-orange-500 rounded-full shadow-lg opacity-0 bottom-4 right-4" :class="scrollPos > 500 ? 'opacity-100' : 'opacity-0'">
            <i class="fa-solid fa-chevron-up" />
        </button>
        <!-- / -->

        <div class="pt-8" />

        <!-- search bar mobile -->
        <Modal :isOpen="isOpenModalFilters" @close="isOpenModalFilters = false">
            <template #title>
                Filtra
            </template>

            <template #description>
                <form @submit.prevent="submit()" class="flex flex-col gap-4">
                    <SearchLocation v-model="form.location" label="Location" />
                    <RangeSlider v-model="form.radius" :min="50" :max="500" :step="10" label="Raggio" unit="km" :disabled="!form.location" />
                    <Input type="dateTime-local" v-model="form.start" placeholder="Inizio" label="Data e ora d'inizio"  :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" class="grow" />
                    <div class="flex w-full gap-4">
                        <NumberInput v-model="form.duration" :min="1" :max="24" label="Durata" unit="ore" class="grow" />
                        <NumberInput v-model="form.guests" :min="1" :max="99" label="Persone" class="grow" />
                    </div>
                    <Input type="search" v-model="form.name" placeholder="Nome studio" label="Nome Studio" />
                    <Input type="search" v-model="form.equip" placeholder="Equipaggiamento" label="Equipaggiamento" />
                    <div class="mt-4 space-y-4">
                        <Button type="submit" text="Cerca" icon="fa-solid fa-magnifying-glass" class="w-full" />
                        <Button @click="reset()" icon="fa-solid fa-arrow-rotate-left" color="slate" text="Reset filtri" :disabled="form.processing" class="w-full" />
                    </div>
                </form>
            </template>
        </Modal>
        <!-- / -->
    </FrontofficeLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import SearchLocation from './SearchLocation.vue';
import RoomCard from './RoomCard.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import Spinner from '@/Components/Spinner.vue';
import RangeSlider from '@/Components/Form/RangeSlider.vue';
import dayjs from 'dayjs';

const props = defineProps({
    rooms: Object,
    request: Object,
});

const showMap = ref(false);
const scrollPos = ref(0);
const isOpenModalFilters = ref(false);

const form = useForm({
    start: props.request?.start ?? null,
    duration: props.request?.duration ?? 2,
    name: props.request?.name ?? null,
    location: props.request?.location ?? null,
    radius: props.request?.radius ?? 100,
    category: props.request?.category ?? '',
    equip: props.request?.equip ?? null,
    guests: props.request?.guests ?? 1,
});

const submit = ()=>{
    isOpenModalFilters.value = false;
    form.get(route('rooms.index'), {
        preserveScroll: true,
        preserveState: true,
    });
};

const reset = ()=>{
    form.reset();
    router.get(route('rooms.index'));
    isOpenModalFilters.value = false;
};

const studios = computed(()=>{
    let studios = [];
    props.rooms.data.forEach(room => {
        studios.push(room.studio);
    })

    return studios;
});

//pulsante torna su
const toTop = ()=>{
    window.scrollTo(0, 0);
};

const getScrollPos = ()=>{
    return scrollPos.value = window.top.scrollY;
};

onMounted(()=>{
    window.addEventListener('scroll', getScrollPos);
});

onUnmounted(()=>{
    window.removeEventListener('scroll', getScrollPos);
});

</script>
