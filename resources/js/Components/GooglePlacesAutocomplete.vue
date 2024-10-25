<template>
    <Input
        v-model="vModel"
        :label="props.label"
        :placeholder="props.placeholder"
        ref="inputGooglePlaces"
        @keypressEnter="emit('enterKeyPress')"
        @change="validateAddressInput()"
        icon="fa-solid fa-location-dot"
        :error="error"
    />
</template>

<script setup>
import { ref } from 'vue';
import FieldError from '@/Components/Form/FieldError.vue';
import Label from '@/Components/Form/Label.vue';
import Input from '@/Components/Form/Input.vue';
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
    error: String,
});

const emit = defineEmits(['error', 'enterKeyPress']);

const error = ref(props.error ?? null);
const selectedPlace = ref(null);

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
        const autocomplete = new Autocomplete(inputGooglePlaces.value.inputElement, options);

        autocomplete.addListener('place_changed', ()=>{
            const place = autocomplete.getPlace();
            
            if(place && place.formatted_address && place.address_components.length){
                vModel.value = place.formatted_address;
                selectedPlace.value = place.formatted_address;
                error.value = null;

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
            } else {
                vModel.value = null;
            }
        });
    }
});

// Validazione dell'input per assicurarsi che l'indirizzo corrisponda a quello dei suggerimenti Google
const validateAddressInput = ()=>{
    if(vModel.value !== selectedPlace.value) {
        error.value = 'Seleziona un indirizzo valido dai suggerimenti di Google.';
        emit('error');
    }
};

</script>
