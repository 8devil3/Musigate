<template>
    <FrontofficeLayout title="Cerca">
        <!-- search bar desktop-->
        <div class="sticky top-0 z-50 border-b bg-slate-950/60 border-slate-700 backdrop-blur-md">
            <form @submit.prevent="submit()" class="items-end justify-center hidden max-w-4xl px-6 py-4 mx-auto md:flex gap-x-2 gap-y-4">
                <ComboBox
                    v-model="form.location"
                    @selected="submit()"
                    @clear="submit()"
                    :options="province"
                    placeholder="Digita una provincia"
                    inputIcon="fa-solid fa-location-dot"
                    listIcon="fa-solid fa-location-dot"
                    :disabled="form.processing"
                    class="grow"
                />
                <!-- <Input type="dateTime-local" v-model="form.start" @input="submit()" :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" placeholder="Inizio" label="Data e ora d'inizio" class="grow max-w-48" />
                <NumberInput v-model="form.duration" @input="submit()" @change="submit()" :min="1" :max="24" label="Durata" unit="ore" />
                <NumberInput v-model="form.guests" @input="submit()" @change="submit()" :min="1" :max="99" label="Persone" /> -->

                <Input v-model="form.name" @input="submit()" @clear="submit()" placeholder="Nome studio" class="grow" />
                <Input v-model="form.equip" @input="submit()" @clear="submit()" placeholder="Equipaggiamento" class="grow" />
                <Button @click="showMap = !showMap" title="Mostra/Nascondi mappa" :color="showMap ? 'green' : 'orange'" icon="fa-solid fa-map-location-dot" />
                <Button @click="reset()" icon="fa-solid fa-arrow-rotate-left" color="slate" title="Reset filtri" />
            </form>
        </div>
        <!-- / -->

        <!-- search bar mobile -->
        <div class="sticky top-0 z-50 flex w-full gap-2 p-4 border-b md:hidden bg-slate-950/60 border-slate-700 backdrop-blur-md">
            <Button @click="isOpenModalFilters = true" text="Filtra" icon="fa-solid fa-sliders" :disabled="form.processing" class="grow" />
            <Button @click="mapFullScreen()" title="Mostra/nascondi mappa" icon="fa-solid fa-map-location-dot" color="orange" :disabled="form.processing" class="shrink-0" />
        </div>
        <!-- / -->

        <!-- mappa -->
        <Transition name="map">
            <div v-if="!form.processing" v-show="showMap" class="fixed z-[100] inset-0 md:relative md:h-96">
                <GoogleMaps
                    :studios="props.studios.data"
                    :lat="props.request?.location?.lat"
                    :lon="props.request?.location?.lon"
                    class="h-full"
                />

                <button type="button" @click="showMap = false" class="md:hidden absolute rounded-full z-[120] px-3 py-1.5 font-normal text-xs leading-none bg-slate-950/80 border border-orange-500 top-4 left-4 uppercase">
                    <i class="mr-1 fa-solid fa-xmark" />
                    chiudi mappa
                </button>
            </div>
        </Transition>
        <!-- / -->

        <div class="px-4 mt-4 text-sm md:px-6">
            Studio trovati: <span class="font-normal text-orange-500">{{ props.studios.total }}</span>
        </div>

        <!-- risultati ricerca -->
        <div v-if="!form.processing" class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 md:gap-6 md:p-6">
            <template v-if="props.studios.data.length">
                <StudioCard v-for="studio in props.studios.data" :studio="studio" />
            </template>

            <p v-else class="p-12 text-xl font-semibold text-center md:text-2xl col-span-full text-slate-500">
                Nessun risultato :(
            </p>
        </div>

        <div v-else class="flex items-center justify-center p-12 mt-12">
            <Spinner class="size-12 orange" />
        </div>
        <!-- / -->

        <!-- paginazione -->
        <Pagination v-if="!form.processing" :data="props.studios.data" :links="props.studios.links" />
        <!-- / -->

        <!-- to top -->
        <button type="button" @click="toTop()" class="fixed flex items-center justify-center text-lg leading-none transition-opacity bg-orange-500 rounded-full shadow-lg opacity-0 size-10 bottom-4 right-4" :class="scrollPos > 500 ? 'opacity-100' : 'opacity-0'">
            <i class="fa-solid fa-chevron-up" />
        </button>
        <!-- / -->

        <div class="pt-8" />

        <!-- filtri mobile -->
        <Modal :isOpen="isOpenModalFilters" @close="isOpenModalFilters = false">
            <template #title>
                Filtra
            </template>

            <template #description>
                <form @submit.prevent="submit()" class="flex flex-col gap-4">
                    <ComboBox
                        v-model="form.location"
                        :options="province"
                        placeholder="Digita una provincia"
                        inputIcon="fa-solid fa-location-dot"
                        listIcon="fa-solid fa-location-dot"
                    />
                    <!-- <Input type="dateTime-local" v-model="form.start" placeholder="Inizio" label="Data e ora d'inizio"  :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" class="grow" />
                    <div class="flex w-full gap-4">
                        <NumberInput v-model="form.duration" :min="1" :max="24" label="Durata" unit="ore" class="grow" />
                        <NumberInput v-model="form.guests" :min="1" :max="99" label="Persone" class="grow" />
                    </div> -->
                    <Input v-model="form.name" placeholder="Nome studio" />
                    <Input v-model="form.equip" placeholder="Equipaggiamento" />
                    <Button type="submit" text="Cerca" icon="fa-solid fa-magnifying-glass" class="w-full" />
                    <Button @click="reset()" icon="fa-solid fa-arrow-rotate-left" color="slate" text="Reset filtri" :disabled="form.processing" class="w-full" />
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
import StudioCard from './StudioCard.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
// import NumberInput from '@/Components/Form/NumberInput.vue';
import ComboBox from '@/Components/Form/ComboBox.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import Spinner from '@/Components/Spinner.vue';
// import RangeSlider from '@/Components/Form/RangeSlider.vue';
import province from './province.json';
// import dayjs from 'dayjs';

const props = defineProps({
    studios: Object,
    request: Object,
});

const showMap = ref(false);
const scrollPos = ref(0);
const isOpenModalFilters = ref(false);

const form = useForm({
    // start: props.request?.start ?? null,
    // duration: props.request?.duration ?? 2,
    // guests: props.request?.guests ?? 1,
    location: props.request?.location ?? null,
    name: props.request?.name ?? null,
    equip: props.request?.equip ?? null,
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

const mapFullScreen = ()=>{
    isOpenModalFilters.value = false;
    showMap.value = true;
};

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
