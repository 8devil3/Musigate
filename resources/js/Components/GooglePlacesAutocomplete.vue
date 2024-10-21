<template>
    <div>
        <label for="google-autocomplete" class="w-full px-3 mb-1.5 block text-xs font-medium leading-tight truncate">{{ props.label }}</label>
        <div class="relative">
            <i class="absolute text-xs leading-none text-orange-500 -translate-y-1/2 fa-solid fa-location-dot top-1/2 left-3" />

            <input type="search" @keypress.enter="emit('enterKeyPress', $event.preventDefault())" v-model="vModel" ref="inputGooglePlaces" :placeholder="props.placeholder" :required="props.required" class="w-full h-8 py-0 pr-3 text-sm font-light text-left text-white truncate border rounded-full pl-7 bg-slate-900 border-slate-400 disabled:bg-slate-800 form-input placeholder:text-slate-500 placeholder:truncate disabled:border-slate-500 disabled:text-slate-500 focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { getGoogleMapsLoader } from '@/Components/GoogleMapsLoader.js';

const props = defineProps({
    label: {
        type: String,
        default: 'Autocompletamento Google',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: 'Digita un indirizzo'
    },
    required: {
        type: Boolean,
        default: false
    },
});

const emit = defineEmits(['addressData', 'enterKeyPress']);

const vModel = defineModel({default: null});

const addressData = ref({
    address: null,
    number: null,
    cap: null,
    city: null,
    province: null,
});

//Google Places API
const inputGooglePlaces = ref(null);

getGoogleMapsLoader().importLibrary('places').then(({ Autocomplete }) => {
    const options = {
        componentRestrictions: { country: "it" },
        fields: ['address_components', 'formatted_address'],
        types: ['address'],
        language: 'it',
    };

    if(inputGooglePlaces.value){
        const autocomplete = new Autocomplete(inputGooglePlaces.value, options);

        autocomplete.addListener('place_changed', ()=>{
            const place = autocomplete.getPlace();

            if(place.formatted_address && place.address_components.length){
                vModel.value = place.formatted_address;
    
                for (const component of place.address_components) {    
                    switch (component.types[0]) {
                        case "street_number":
                            addressData.value.number = component.long_name;
                        break;

                        case "route":
                            addressData.value.address = component.short_name;
                        break;

                        case "postal_code":
                            addressData.value.cap = component.long_name ?? component.short_name;
                        break;

                        case "administrative_area_level_2":
                            addressData.value.province = component.long_name;
                        break;

                        case "administrative_area_level_3":
                            addressData.value.city = component.long_name;
                        break;
                    }
                }

                emit('addressData', addressData.value);
            } else {
                vModel.value = null;
            }
        });
    }
});

</script>
