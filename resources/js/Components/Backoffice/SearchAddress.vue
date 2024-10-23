<template>
    <Combobox v-model.trim="selectedAddress" as="div" class="relative">
        <ComboboxInput type="search" ref="input" placeholder="Digita almeno quattro caratteri" 
        :displayValue="(location) => location?.place_name" :class="[commonClasses.input, 'pl-8']" required autocomplete="off" />

        <div class="absolute inset-y-0 flex items-center left-4">
            <i class="mr-1 text-[12px] fa-solid fa-location-dot"></i>
        </div>
        
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <ComboboxOptions as="ul" v-if="locations.length" class="absolute inset-x-0 z-40 max-h-[256px] rounded-xl mt-2 overflow-y-auto text-sm bg-slate-800 border border-slate-600 shadow-lg overforigin-top-right ring-1 ring-orange-500 ring-opacity-5 focus:outline-none">
                <ComboboxOption as="li" v-for="location in locations" :value="location">
                    <button type="button" @click="selectLocation()" class="flex items-center w-full gap-2 px-4 py-2 text-slate-300 truncate transition-colors hover:bg-orange-500 hover:text-white">
                        <i class="text-xs fa-solid fa-location-dot"></i>
                        {{ location.place_name }}
                    </button>
                </ComboboxOption>
            </ComboboxOptions>
        </transition>
    </Combobox>
</template>

<script setup>
import { ref } from 'vue';
import commonClasses from '@/Components/Form/commonClasses.json';
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxOption } from '@headlessui/vue';


const props = defineProps({
    modelValue: Object
});

const emit = defineEmits(['update:model-value']);

const locations = ref([]);
const selectedAddress = ref(props.modelValue);

const selectLocation = ()=>{
    emit('update:model-value', selectedAddress);
}

// mapbox
// const getAddress = (query)=>{
//     const baseURL = 'https://api.mapbox.com/geocoding/v5/mapbox.places/';
//     const params = {
//         country: 'it',
//         // types: 'address',
//         language: 'it',
//         access_token: 'pk.eyJ1Ijoia3Jvbm9za2V5IiwiYSI6ImNrbTZuaXF1bDBwdHMycm42anh3bzV2YmkifQ.cVjIxWuordZ3a38lJ9S2aQ',
//         fuzzyMatch: true,
//         limit: 10,
//     };

//     if(query.length > 3){
//         locations.value = [];
        
//         axios.get(baseURL + query + '.json', { params }).then((res)=>{
//             if(res.data.features){
//                 res.data.features.forEach(item => {
//                     locations.value.push(item);
//                 });
//             }
//         });
//     }
// }

//Google Geocode API
// const getAddress = ()=>{
//     const baseURL = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=YOUR_API_KEY';

//     const params = {
//         country: 'it',
//         // types: 'address',
//         language: 'it',
//         access_token: 'pk.eyJ1Ijoia3Jvbm9za2V5IiwiYSI6ImNrbTZuaXF1bDBwdHMycm42anh3bzV2YmkifQ.cVjIxWuordZ3a38lJ9S2aQ',
//         fuzzyMatch: true,
//         limit: 10,
//         address: query
//     };

//     if(query.length > 3){
//         locations.value = [];
        
//         axios.get(baseURL + query + '.json', { params }).then((res)=>{
//             if(res.data.features){
//                 res.data.features.forEach(item => {
//                     locations.value.push(item);
//                 });
//             }
//         });
//     }
// }

</script>
