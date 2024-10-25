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
                    <div>
                        <GooglePlacesAutocomplete
                            v-if="!form.is_manual_address"
                            v-model="form.complete_address"
                            @error="form.complete_address = null"
                            label="Autocompletamento Google"
                            required
                        />

                        <div v-else class="grid max-w-md grid-cols-3 gap-x-2 gap-y-4">
                            <Input v-model="form.address" placeholder="Indirizzo, senza numero civico" label="Indirizzo" :error="form.errors.address" :disabled="!form.is_manual_address" required class="col-span-2" />
    
                            <Input v-model="form.number" placeholder="Civico" label="Numero" :error="form.errors.number" :disabled="!form.is_manual_address" class="col-span-1" />
    
                            <Input v-model="form.city" placeholder="Città" label="Città" :error="form.errors.city" :disabled="!form.is_manual_address" required class="col-span-2" />
    
                            <Input v-model="form.cap" placeholder="CAP" label="CAP" pattern="[0-9]{5}" inputmode="numeric" :error="form.errors.cap" :disabled="!form.is_manual_address" :required="form.is_manual_address" class="col-span-1" />
    
                            <Input v-model="form.province" placeholder="Provincia" label="Provincia" :error="form.errors.province" :disabled="!form.is_manual_address" required class="col-span-full" />
                        </div>
                    </div>
                    
                    <div class="px-4 mt-4">
                        <Checkbox v-model="form.is_manual_address">Inserimento manuale indirizzo</Checkbox>
                    </div>

                    <div class="mt-4">
                        <GoogleMaps
                            :studios="[props.studio]"
                            :lat="props.studio.location.lat"
                            :lon="props.studio.location.lon"
                            :zoom="18"
                            :enableMarkerLink="false"
                            class="h-64 border border-slate-400 overflow-clip rounded-xl"
                        />
                    </div>
                </template>
            </FormElement>

            <FormElement optional>
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
        
        <template v-if="form.isDirty && !form.processing" #actions>
            <Button @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import GooglePlacesAutocomplete from '@/Components/GooglePlacesAutocomplete.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';

const props = defineProps({
    studio: Object,
});

const form = useForm({
    complete_address: props.studio.location.complete_address,
    address: props.studio.location.address,
    number: props.studio.location.number,
    cap: props.studio.location.cap,
    city: props.studio.location.city,
    province: props.studio.location.province,
    notes: props.studio.location.notes,
    is_manual_address: props.studio.location.is_manual_address,
});

const submit = () => {
    if(form.processing) return;
    if(form.is_manual_address) form.complete_address = null;
    form.put(route('studio.location.update'), {
        preserveState: false,
    });
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
