<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Location"
        icon="fa-solid fa-location-dot"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement required>
                <template #title>
                    Indirizzo completo
                </template>

                <template #description>
                    Inizia a digitare per abilitare l'autocompletamento di Google.<br>
                    Se l'autocompletamento non fosse accurato puoi inserire l'indirizzo manualmente spuntando la casella di inserimento manuale.
                </template>

                <template #content>
                    <div class="mb-4">
                        <label for="google-autocomplete" class="w-full px-3 mb-1 text-xs font-medium leading-tight truncate">Autocompletamento Google</label>
                        <div class="relative">
                            <i class="absolute text-sm leading-none text-gray-400 -translate-y-1/2 fa-solid fa-location-dot top-1/2 left-4"></i>

                            <input type="search" @keypress.enter="$event.preventDefault()" v-model="form.complete_address" id="google-autocomplete" ref="inputGooglePlaces" placeholder="Inizia a digitare un indirizzo" class="w-full h-8 py-0 pr-4 text-sm text-left text-white truncate bg-gray-900 border border-gray-400 rounded-full pl-9 disabled:bg-gray-800 form-input placeholder:text-gray-300/80 placeholder:truncate disabled:border-gray-500 disabled:text-gray-500 focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500" />
                        </div>
                    </div>

                    <fieldset :disabled="!isManual" class="space-y-4">
                        <div class="flex w-full gap-2">
                            <Input v-model="form.address" placeholder="Indirizzo, senza numero civico" label="Indirizzo" id="studio-location-address" :error="form.errors.address" class="grow" required />
                            <Input v-model="form.number" placeholder="Civico" label="Numero" id="studio-location-number" :error="form.errors.number" class="w-24" />
                        </div>
                        <div class="flex w-full gap-2">
                            <Input v-model="form.city" placeholder="Città" label="Città" id="studio-location-city" :error="form.errors.city" class="grow" required />
                            <Input v-model="form.cap" placeholder="CAP" label="CAP" pattern="[0-9]{5}" :error="form.errors.cap" class="w-24" required />
                        </div>
                        <Input v-model="form.province" placeholder="Provincia" label="Provincia" :error="form.errors.province" required />
                    </fieldset>
                    
                    <div class="px-4 mt-4">
                        <Checkbox v-model="isManual" id="location-manual-insert">Inserimento manuale indirizzo</Checkbox>
                    </div>
                </template>
            </FormElement>

            <FormElement>
                <template #title>
                    Indicazioni per arrivare
                </template>

                <template #description>
                    Puoi inserire delle indicazioni aggiuntive per agevolare l'arrivo degli artisti.<br>
                    Max 255 caratteri, spazi esclusi.
                </template>

                <template #content>
                    <Textarea :rows="6" v-model="form.notes" placeholder="Indicazioni per arrivare allo Studio" :maxlength="255" :error="form.errors.notes" class="w-full" />
                </template>
            </FormElement>
        </template>
        
        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import { Loader } from '@googlemaps/js-api-loader';

const props = defineProps({
    location: Object,
});

const form = useForm({
    complete_address: props.location.complete_address,
    address: props.location.address,
    number: props.location.number,
    cap: props.location.cap,
    city: props.location.city,
    province: props.location.province,
    lon: props.location.lon,
    lat: props.location.lat,
    notes: props.location.notes
});

const isManual = ref(false);

const submit = () => {
    form.put(route('studio.location.update'), {
        preserveScroll: true,
    });
};

//Google Places API
const inputGooglePlaces = ref(null);

const loader = new Loader({
    apiKey: import.meta.env.VITE_GOOGLE_API_KEY,
    version: 'quarterly',
    libraries: ['places']
});

loader.importLibrary('places').then(({ Autocomplete }) => {
    const options = {
        componentRestrictions: { country: "it" },
        fields: ['address_components', 'formatted_address'],
        types: ['address'],
        language: 'it',
    };

    const autocomplete = new Autocomplete(inputGooglePlaces.value, options);
    
    autocomplete.addListener('place_changed', ()=>{
        form.address = null;
        form.number = null;
        form.cap = null;
        form.city = null;
        form.province = null;
        form.lon = null;
        form.lat = null;
        
        const place = autocomplete.getPlace();
        form.complete_address = place.formatted_address;
                
        for (const component of place.address_components) {    
            switch (component.types[0]) {
                case "street_number": {
                    form.number = component.long_name;
                    break;
                }
    
                case "route": {
                    form.address = component.short_name;
                    break;
                }
    
                case "postal_code": {
                    form.cap = component.long_name ?? component.short_name;
                    break;
                }
                
                case "administrative_area_level_2": {
                    form.province = component.long_name;
                    break;
                }
 
                case "administrative_area_level_3": {
                    form.city = component.long_name;
                    break;
                }
            }
        }
    });
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Location',
    }, {default: () => page}),
};
</script>
