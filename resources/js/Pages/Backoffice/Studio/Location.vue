<template>
    <ContentLayout
        @submitted="submit()"
        title="Location"
        icon="fa-solid fa-location-dot"
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
                    <div class="grid max-w-md grid-cols-3 gap-x-2 gap-y-4">
                        <GooglePlacesAutocomplete
                            v-model="form.complete_address"
                            @addressData="setFormAddress"
                            class="col-span-full"
                        />
                        <Input v-model="form.address" placeholder="Indirizzo, senza numero civico" label="Indirizzo" :error="form.errors.address" :disabled="!isManualAddress" required class="col-span-2" />

                        <Input v-model="form.number" placeholder="Civico" label="Numero" :error="form.errors.number" :disabled="!isManualAddress" class="col-span-1" />

                        <Input v-model="form.city" placeholder="Città" label="Città" :error="form.errors.city" :disabled="!isManualAddress" required class="col-span-2" />

                        <Input v-model="form.cap" placeholder="CAP" label="CAP" pattern="[0-9]{5}" :error="form.errors.cap" :disabled="!isManualAddress" :required="isManualAddress" class="col-span-1" />

                        <Input v-model="form.province" placeholder="Provincia" label="Provincia" :error="form.errors.province" :disabled="!isManualAddress" required class="col-span-full" />
                    </div>
                    
                    <div class="px-4 mt-4">
                        <Checkbox v-model="isManualAddress">Inserimento manuale indirizzo</Checkbox>
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
            <SaveButton v-if="form.isDirty && !form.processing" />
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
import GooglePlacesAutocomplete from '@/Components/GooglePlacesAutocomplete.vue';

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
    notes: props.location.notes
});

const isManualAddress = ref(false);

const submit = () => {
    form.put(route('studio.location.update'));
};

const setFormAddress = (e)=>{
    form.address = e.address;
    form.number = e.number;
    form.cap = e.cap;
    form.city = e.city;
    form.province = e.province;
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Location',
    }, {default: () => page}),
};
</script>
