<template>
    <ContentLayout
        @submitted="submit()"
        :title="'Disponibilità: ' + props.weekdays[props.availability.weekday]"
        icon="fa-solid fa-clock"
        backRoute="studio.availability.index"
    >
        <template #content>
            <!-- tipo di apertura/chiusura -->
            <FormElement>
                <template #title>
                    Tipo di apertura
                </template>
                <template #description>
                    Scegli se {{ props.availability.weekday === 8 ? 'nei ' + props.weekdays[props.availability.weekday] : 'di ' + props.weekdays[props.availability.weekday] }} lo Studio è aperto con orari tradizionali, con orari overnight (es: dalle 18:00 alle 02:00 del giorno successivo), tutto il giorno (24h), su richiesta con preavviso, o chiuso.

                    <div v-if="props.availability.weekday === 8" class="mt-4 space-y-2">
                        <p>Sono considerati giorni festivi quelli stabiliti per legge, ad eccezione della domenica che è possibile impostare separatamente. Giorni festivi:</p>

                        <ul class="list-disc">
                            <li v-for="holyday in props.holydays" class="pb-0.5">{{ holyday }}</li>
                        </ul>
                    </div>

                    <div v-if="Object.keys(usePage().props.errors).length" class="mt-4 font-normal text-red-500">
                        Errori
                        <ul class="list-disc">
                            <li v-for="error in usePage().props.errors">{{ error }}</li>
                        </ul>
                    </div>
                </template>
                <template #content>
                    <div class="space-y-2">
                        <Radio v-for="openType, key in props.open_types" v-model="form.open_type" @change="setOpenHours()" name="studio-open-type" :value="key">{{ openType }}</Radio>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- orari di lavoro -->
            <FormElement v-if="form.open_type === 'open' || form.open_type === 'open_overnight'">
                <template #title>
                    Orari di apertura
                </template>
                <template #description>
                    Imposta gli orari di lavoro settimanali dello Studio.
                </template>
                <template #content>
                    <div class="space-x-2">
                        <Select
                            v-model="form.open_start"
                            isArray
                            @change="validations()"
                            :options="props.hours"
                            default="Inizio"
                            :error="form.errors.open_start"
                            required
                            class="w-28"
                        />

                        <Select
                            v-model="form.open_end"
                            isArray
                            @change="validations()"
                            :options="form.open_type === 'open_overnight' ? hours : hours.filter(h => h > form.open_start)"
                            default="Fine"
                            :error="form.errors.open_end"
                            :disabled="!form.open_start"
                            required
                            class="w-28"
                        />
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- fasce orarie -->
            <FormElement v-if="form.open_type !== 'close' && form.open_start && form.open_end">
                <template #title>
                    Fasce orarie
                </template>
                <template #description>
                    Imposta le fasce orarie del {{ props.weekdays[props.availability.weekday] }}.<br>
                    Le fasce orarie sono necessarie quando vuoi impostare le tariffe basate sulle fasce orarie.<br>
                    Ricordati di salvare anche quando elimini delle fasce orarie.<br>
                    Le fasce orarie, se inserite, devono essere almeno due e devono avere nomi univoci nello stesso giorno; possono avere lo stesso nome se in giorni diversi.<br>
                    La fine dell'ultima fascia deve corrispondere all'orario di chiusura dello Studio.

                    <div class="mt-2 font-light text-red-500">
                        <strong>ATTENZIONE:</strong> eliminando le fasce orarie verranno eliminate le tariffe delle Sale e dei pacchetti associati, se presenti.
                    </div>
                </template>
                <template #content>
                    <template v-if="form.timebands.length">
                        <InfoBlock
                            v-show="hasValidationErrors"
                            color="danger"
                            :title="hasValidationErrors.title"
                            class="mb-4"
                        >
                            {{ hasValidationErrors.message }}
                        </InfoBlock>

                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">nome</th>
                                    <th class="text-center">inizio</th>
                                    <th class="text-center">fine</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="timeband, index in form.timebands">
                                    <td class="w-full text-center min-w-32">
                                        <Input v-model="timeband.name" @change="validations()" placeholder="Nome fascia oraria" autofocus required />
                                    </td>
                                    <td class="text-center">
                                        <Input
                                            v-model="timeband.start"
                                            disabled
                                            required
                                            class="w-24"
                                        />
                                    </td>
                                    <td class="text-center">
                                        <Select
                                            v-model="timeband.end"
                                            @change="validations()"
                                            isArray
                                            default="Fine"
                                            :options="form.open_type === 'open_overnight' ? hours : hours.filter(h => h <= form.open_end && h > timeband.start)"
                                            :disabled="!timeband.start"
                                            required
                                            class="w-24"
                                        />
                                    </td>
                                    <td class="w-10 text-center">
                                        <ActionButton @click="deleteTimeband(index, timeband.id)" icon="fa-solid fa-trash-can" color="red" title="Elimina" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-2">
                            <button type="button" v-if="form.timebands[form.timebands.length -1].end !== form.open_end" @click="addTimeband()" class="p-1 text-sm font-semibold text-orange-500 transition-colors hover:text-orange-400 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-plus" />
                                Aggiungi
                            </button>
                        </div>
                    </template>
                    
                    <Empty v-else icon="fa-solid fa-clock">
                        <template #title>
                            Nessuna fascia oraria
                        </template>
                        <template #description>
                            Non sono presenti fasce orarie per {{ props.weekdays[props.availability.weekday] }}.
                        </template>
                        <template #actions>
                            <Button @click="addTimeband()" text="Aggiungi" icon="fa-solid fa-plus" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
            <!-- / -->

            <!-- apertura su richiesta -->
            <FormElement v-if="form.open_type === 'open_forewarning'">
                <template #title>
                    Preavviso
                </template>
                <template #description>
                    Imposta il preavviso espresso in giorni.
                </template>
                <template #content>
                    <NumberInput v-model="form.min_forewarning" unit="giorni" :min="1" :max="250" required />
                </template>
            </FormElement>
            <!-- / -->

            <!-- copia disponibilità -->
            <FormElement>
                <template #title>
                    Copia disponibilità
                </template>
                <template #description>
                    Se hai già impostato almeno un giorno della settimana da ripetere puoi copiarlo senza dover inserire nuovamente gli stessi dati, comprese le fasce orarie. Per procedere, seleziona il giorno da cui copiare i dati e clicca "Copia".

                    <div class="mt-2 font-light text-red-500">
                        <strong>ATTENZIONE:</strong> copiando la disponibiltà verranno eliminate le tariffe delle Sale e dei pacchetti associati, se presenti.
                    </div>
                </template>
                <template #content>
                    <div class="flex gap-2">
                        <Select v-model="cloneFromWeekday" :options="props.copy_from_weekdays" default="Copia dati dal giorno..." class="w-64" />
                        <Button @click="cloneAvailability()" text="Copia" icon="fa-solid fa-copy" :disabled="!cloneFromWeekday" />
                    </div>
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template v-if="form.isDirty && !form.processing && !hasValidationErrors" #actions>
            <Button @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import InfoBlock from '@/Components/InfoBlock.vue';
import Button from '@/Components/Form/Button.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Input from '@/Components/Form/Input.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Radio from '@/Components/Form/Radio.vue';

const props = defineProps({
    availability: Object,
    current_weekday: Number,
    hours: Object,
    weekdays: Object,
    holydays: Object,
    open_types: Object,
    copy_from_weekdays: Object,
});

const hasValidationErrors = ref(false);
const cloneFromWeekday = ref('');

const form = useForm({
    open_type: props.availability.open_type,
    open_start: props.availability.open_start,
    open_end: props.availability.open_end,
    min_forewarning: props.availability.min_forewarning,
    timebands: props.availability.timebands ?? [],
});

const submit = ()=>{
    if(form.processing || hasValidationErrors.value) return;
    form.put(route('studio.availability.update', props.availability.id), {
        preserveState: false,
    });
};

const cloneAvailability = ()=>{
    router.put(route('studio.availability.clone', props.availability.id), {clone_from_weekday: cloneFromWeekday.value}, {
        preserveState: false
    });
};

const addTimeband = ()=>{
    let timeband = {
        id: null,
        availability_id: null,
        name: null,
        start: form.open_start,
        end: null,
    };

    if(form.timebands.length){
        let index = form.timebands.length;
        timeband.start = form.timebands[index -1].end;
        timeband.end = null;
    }

    form.timebands.push(timeband);

    validations();
};

const deleteTimeband = (index)=>{
    form.timebands.splice(index, 1);
    validations();
};

const setTimebandsStartEnd = ()=>{
    form.timebands.forEach((timeband, index) => {
        timeband.start = form.open_start;
        if(index) timeband.start = form.timebands[index -1].end;
    });
};

const setOpenHours = ()=>{
    if(form.open_type === 'open_h24'){
        form.open_start = '00:00';
        form.open_end = '24:00';
    } else {
        form.open_start = '';
        form.open_end = '';
    }

    validations();
};

const validations = ()=>{
    setTimebandsStartEnd();
    hasValidationErrors.value = false;

    if(form.open_type !== 'close'){
        //validazione orari delle fasce orarie
        if(
            form.timebands.length &&
            (form.timebands[0].start !== form.open_start ||
            form.timebands[form.timebands.length -1].end !== form.open_end)
        ){
            hasValidationErrors.value = {
                title: 'Orario di fine non coerente con chiusura',
                message: 'L\'orario di fine dell\'ultima fascia oraria deve coincidere con quella di chiusura dello Studio.'
            };
        }
    
        //validazione numero di fasce orarie (minimo 2)
        if(form.timebands.length && form.timebands.length < 2){
            hasValidationErrors.value = {
                title: 'Fascia oraria singola',
                message: 'Devi inserire almeno due fasce orarie.'
            };
        }
    
        //validazione nomi univoci
        let timebandNames = form.timebands.map(tb => tb.name);
        let hasDuplicates = new Set(timebandNames).size !== timebandNames.length;
        
        if(hasDuplicates){
            hasValidationErrors.value = {
                title: 'Nome fascia non univoco',
                message: 'Le fasce orarie devono avere nomi univoci nello stesso giorno. Possono avere lo stesso nome in giorni diversi.'
            };
        }
    }
};

const hours = computed(()=>{
    let hours = props.hours;
    if(form.open_type === 'open_h24' && !hours.includes('24:00')) hours.push('24:00');
    if(form.open_type === 'open' && hours.includes('24:00')) hours.splice(hours.indexOf(h => h === '24:00'), 1);
    return hours;
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>
