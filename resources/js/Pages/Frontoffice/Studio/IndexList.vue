<template>
    <FrontofficeLayout title="Cerca Studi">
        <div class="block w-full pb-4 mx-auto lg:flex lg:items-start max-w-7xl md:py-6">
            <!-- mobile barra ricerca -->
            <div class="sticky top-0 z-50 flex gap-2 p-4 mb-4 border-b border-gray-800 backdrop-blur-md bg-gray-900/20 lg:hidden md:p-6">
                <SearchLocation v-model="form.location" @update:model-value="submit()" class="grow" />
                <Button @click="openModalFilters = true" icon="fa-solid fa-sliders" title="Filtri" />
                <Button @click="form.showMap = !form.showMap" icon="fa-solid fa-map-location-dot" title="Mappa" />
            </div>

            <!-- colonna filtri desktop -->
            <div class="hidden p-4 border border-gray-500 shadow-inner w-72 lg:block shrink-0 rounded-xl bg-gray-900/50">
                <div class="w-full pl-1">                    
                    <form @submit.prevent="submit()" class="space-y-12">
                        <!-- località e distanza -->
                        <div class="space-y-4">
                            <!-- on/off mappa -->
                            <Toggle v-model="form.showMap" label="Mappa" />
                            <!-- / -->

                            <h3>
                                <i class="mr-1 text-lg fa-solid fa-location-dot"></i>
                                Località
                            </h3>
                            <SearchLocation v-model="form.location" @update:model-value="submit()"/>

                            <div class="space-y-2">
                                <RangeSlider symbol="km" v-model.number="form.radius" @change="submit()" :step="10"/>
                                <div class="flex items-center justify-between font-sans text-base tracking-wide text-gray-300">
                                    <span>Distanza</span>
                                    <span class="font-sans text-sm">fino a <span class="text-sm font-medium text-white">{{ form.radius }} km</span></span>
                                </div>
                            </div>
                        </div>
                        <!-- / -->

                        <!-- categoria -->
                        <div class="space-y-4">
                            <h3>
                                <i class="mr-1 text-lg fa-solid fa-record-vinyl"></i>
                                Categoria Studio
                            </h3>
                            <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Professional']" id="category-professional">
                                Professional
                            </Radio>
                            <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Home']" id="category-home">
                                Home
                            </Radio>
                            <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Professional', 'Home']" id="category-both">
                                Entrambi
                            </Radio>
                        </div>
                        <!-- / -->

                        <!-- equipaggiamento -->
                        <div class="space-y-4">
                            <h3>
                                <i class="mr-1 text-lg fa-solid fa-microphone-lines"></i>
                                Equipaggiamento
                            </h3>
                            <Input type="search" v-model="form.equip" @update:model-value="form.equip.length > 3 || form.equip === '' ? submit() : null" placeholder="Cerca equipaggiamento" icon="fa-solid fa-microphone-lines"/>
                        </div>
                        <!-- / -->
                        
                        <!-- servizi -->
                        <div class="space-y-4">
                            <h3>
                                <i class="mr-1 text-lg fa-solid fa-hand-holding-heart"></i>
                                Servizi
                            </h3>
                            <div class="space-y-3">
                                <Checkbox v-for="service, key in props.services" v-model="form.services" :value="key" @change="submit()" :id="'filter-service-' + key" class="capitalize">
                                    {{ service }}
                                </Checkbox>
                            </div>
                        </div>
                        <!-- / -->

                        <!-- comfort -->
                        <div class="space-y-4">
                            <h3>
                                <i class="mr-1 text-lg fa-solid fa-hand-holding-heart"></i>
                                Comfort
                            </h3>
                            <div class="space-y-3">
                                <Checkbox v-for="comfort, key in props.comforts" v-model="form.comforts" :value="key" @change="submit()" :id="'filter-comfort-' + key" class="capitalize">
                                    {{ comfort }}
                                </Checkbox>
                            </div>
                        </div>
                        <!-- / -->
                    </form>
                </div>
            </div>
            <!-- / -->
            
            <!-- colonna risultati -->
            <div class="px-4 md:grow md:px-6">
                <div class="flex flex-col gap-4 md:gap-6">            
                    <!-- mappa -->
                    <TransitionRoot :show="form.showMap" 
                        enter="transform transition duration-[400ms]"
                        enter-from="-translate-y-1/2 scale-y-0"
                        enter-to="translate-y-0 scale-y-100"
                        leave="transform duration-200 transition ease-in-out"
                        leave-from="translate-y-0 scale-y-100"
                        leave-to="-translate-y-1/2 scale-y-0"
                    >
                        <GoogleMaps :studios="props.studios.data" :center="{lat: props.request.location.lat, lon: props.request.location.lon}" class="border border-gray-600 h-80 rounded-xl" />
                    </TransitionRoot>
                    <!-- / -->
            
                    <!-- qtà risultati -->
                    <div v-if="props.studios.data.length" class="text-sm">
                        Studi trovati: 
                        {{ props.studios.total }}
                    </div>
                    <!-- / -->
            
                    <!-- risultati lista -->
                    <template v-if="props.studios.data.length">
                        <SearchResultItem v-for="studio in props.studios.data" :studio="studio"/>
                    </template>
                    
                    <!-- risultati griglia -->
                    <!-- <div v-if="props.studios" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <SearchResultCard v-for="studio in props.studios" :studio="studio"/>
                    </div> -->
                    <!-- / -->
            
                    <p v-else class="mt-16 text-3xl font-semibold text-center text-gray-500 md:mt-64 md:text-4xl grow">
                        Nessun risultato<br>:(
                    </p>
                    <!-- / -->
                </div>

                <Pagination v-if="props.studios.total && props.studios.last_page > 1" :pagination="props.studios" />
            </div>
            <!-- / -->
        </div>
    </FrontofficeLayout>

    <Modal :isOpen="openModalFilters" @close="openModalFilters = false" maxWidth="w-full max-w-[480px]">
        <template #title>
            Filtri
        </template>
        <template #description>
            <div>
                <!-- filtri -->
                <div class="space-y-10">
                    <!-- distanza -->
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between tracking-wide text-gray-300 font-lemon">
                            <span >Distanza</span>
                            <span class="font-sans text-sm">fino a <span class="text-sm font-medium text-white">{{ form.radius }} km</span></span>
                        </div>
                        <RangeSlider symbol="km" v-model.number="form.radius" :step="10"/>
                    </div>
                    <!-- / -->

                    <!-- equipaggiamento -->
                    <div class="space-y-4">
                        <h3>
                            <i class="mr-1 text-lg fa-solid fa-microphone-lines"></i>
                            Equipaggiamento
                        </h3>
                        <Input type="search" v-model="form.equip" placeholder="Cerca equipaggiamento" icon="fa-solid fa-microphone-lines"/>
                    </div>
                    <!-- / -->

                    <!-- categoria -->
                    <div class="space-y-4">
                        <h3>
                            <i class="mr-1 text-lg fa-solid fa-record-vinyl"></i>
                            Categoria Studio
                        </h3>
                        <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Professional']" id="mobile-category-professional">
                            Professional
                        </Radio>
                        <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Home']" id="mobile-category-home">
                            Home
                        </Radio>
                        <Radio @change="submit()" v-model="form.category" name="search-studio-category" :value="['Professional', 'Home']" id="mobile-category-both">
                            Entrambi
                        </Radio>
                    </div>
                    <!-- / -->

                    <div class="flex flex-wrap gap-8">
                        <!-- servizi -->
                        <div class="flex flex-col gap-3">
                            <div class="tracking-wide font-lemon">Servizi</div>
                            <div class="grid grid-cols-1 gap-x-12 gap-y-3 md:grid-cols-2">
                                <Checkbox v-for="service, id in props.services" :key="id" v-model="form.services" :id="'search-service-' + id" :value="id" class="capitalize">
                                    {{ service }}
                                </Checkbox>
                            </div>
                        </div>
                        <!-- / -->

                        <!-- comfort -->
                        <div class="flex flex-col gap-3">
                            <div class="tracking-wide font-lemon">Comfort</div>
                            <div class="grid grid-cols-1 gap-x-12 gap-y-3 md:grid-cols-2">
                                <Checkbox v-for="comfort, id in props.comforts" :key="id" v-model="form.comforts" :id="'search-comfort-' + id" :value="id" class="capitalize">
                                    {{ comfort }}
                                </Checkbox>
                            </div>
                        </div>
                        <!-- / -->
                    </div>
                </div>
                <!-- / -->
            </div>
        </template>
        <template #actions>
            <Button @click="submit()" text="Applica filtri" class="w-full" />
        </template>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import SearchLocation from '@/Components/Frontoffice/Search/SearchLocation.vue';
// import SearchResultCard from '@/Components/Frontoffice/Search/SearchResultCard.vue';
import SearchResultItem from '@/Components/Frontoffice/Search/SearchResultItem.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import Radio from '@/Components/Form/Radio.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import RangeSlider from '@/Components/Form/RangeSlider.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import { TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    studios: Object,
    services: Object,
    comforts: Object,
    request: Object,
});

const openModalFilters = ref(false);

const defaultLocation = {
    istat: "15146",
    comune: "Milano",
    regione: "Lombardia",
    provincia: "Milano",
    sigla_prov: "MI",
    lon: 9.19034740,
    lat: 45.46679408
}

const form = useForm({
    showMap: props.request.showMap === 'true',
    location: props.request.location ?? defaultLocation,
    radius: props.request.radius ?? 100,
    services: props.request.services ?? [],
    comforts: props.request.comforts ?? [],
    category: props.request.category ?? ['Professional', 'Home'],
    equip: props.request.equip ?? ''
});

const submit = ()=>{
    form.get(route('studio.index'), {
        preserveScroll: true,
    });
}

</script>

