<template>
    <FrontofficeLayout title="Cerca Studi">
        <form @submit.prevent="submit()" class="flex h-full">
            <div class="flex flex-col h-full" :class="expandMap ? 'w-0' : 'w-7/12'">
                <!-- filtri -->
                <div class="p-4 space-y-2 border-b bg-slate-950/60 lg:px-6 shrink-0 border-slate-700 backdrop-blur-md">
                    <div class="flex items-end gap-2">
                        <SearchLocation v-model="form.location" @update:model-value="submit()" label="Location" required />
                        <Input type="date" @change="submit()" v-model="form.date" label="Data" required />
                        <Input type="time" @change="submit()" v-model="form.time" label="Ora" required />
                        <NumberInput @change="submit()" v-model="form.duration" :min="1" :max="8" label="Durata (ore)" required class="w-28" />
                        <Input type="search" v-model="form.name" @input="submit()" placeholder="Nome studio" label="Studio" class="grow" />
                        <Button type="submit" icon="fa-solid fa-magnifying-glass" />
                    </div>
                </div>
                <!-- / -->

                <!-- risultati ricerca -->
                <div class="h-0 p-4 space-y-4 overflow-y-auto grow lg:p-6 lg:space-y-6">
                    <template v-if="props.studios.data.length">
                        <SearchResultItem v-for="studio in props.studios.data" :studio="studio"/>
                    </template>

                    <div v-else>
                        <p>Nessuno Studio trovato :(</p>
                    </div>
                </div>
                <!-- / -->
            </div>

            <!-- mappa -->
            <div class="relative h-full" :class="expandMap ? 'w-full' : 'w-5/12'">
                <GoogleMaps
                    :studios="props.studios.data"
                    :lat="parseFloat(props.request.location.lat)"
                    :lon="parseFloat(props.request.location.lon)"
                    class="h-full"
                />

                <button v-if="!expandMap" type="button" @click="expandMap = true" class="absolute flex items-center gap-2 px-4 py-2 text-sm text-white border-2 border-orange-500 rounded-full shadow-md font-title top-4 left-4 bg-slate-800/80">
                    <i class="fa-solid fa-chevron-left" />
                    Espandi mappa
                </button>

                <button v-else type="button" @click="expandMap = false" class="absolute flex items-center gap-2 px-4 py-2 text-sm text-white border-2 border-orange-500 rounded-full shadow-md font-title top-4 left-4 bg-slate-800/80">
                    Riduci mappa
                    <i class="fa-solid fa-chevron-right" />
                </button>
            </div>
            <!-- / -->
        </form>
    </FrontofficeLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import SearchLocation from './SearchLocation.vue';
// import SearchResultCard from './SearchResultCard.vue';
import SearchResultItem from './SearchResultItem.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import dayjs from 'dayjs';

const props = defineProps({
    studios: Object,
    services: Object,
    comforts: Object,
    request: Object,
});

const expandMap = ref(false);

const form = useForm({
    date: props.request.date ?? dayjs().format('YYYY-MM-DD'),
    time: props.request.time ?? dayjs().minute(0).format('HH:mm'),
    duration: props.request.duration ?? 2,
    name: props.request.name ?? null,
    location: props.request.location,
    radius: props.request.radius ?? 100,
    services: props.request.services ?? [],
    comforts: props.request.comforts ?? [],
    category: props.request.category ?? ['Professional', 'Home'],
    equip: props.request.equip ?? ''
});

const submit = ()=>{
    form.get(route('studio.index'), {
        preserveScroll: true,
        preserveState: true,
    });
}

</script>
