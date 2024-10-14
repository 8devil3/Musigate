<template>
    <ContentLayout
        @submitted="submit()"
        title="Disponibilità settimanale"
        icon="fa-solid fa-clock"
    >
        <template #content>
            <!-- copia disponibilità -->
            <FormElement v-if="props.all_timebands">
                <template #title>
                    Copia disponibilità
                </template>
                <template #description>
                    Se hai già impostato almeno un giorno della settimana puoi copiarlo senza dover inserire nuovamente gli stessi dati, comprese le fasce orarie.

                    <div class="mt-2 text-red-500">
                        <strong>ATTENZIONE:</strong> copiando la disponibiltà verranno eliminate TUTTE le tariffe delle Sale associate, se presenti.
                    </div>
                </template>
                <template #content>
                    <Select v-model="cloneFromWeekday" @change="cloneAvailability()" :options="props.all_timebands" default="Copia dati dal giorno..." class="w-64" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- orari di lavoro -->
            <FormElement>
                <template #title>
                    Orari di apertura
                </template>
                <template #description>
                    Imposta gli orari di lavoro settimanali dello Studio.
                </template>
                <template #content>
                    <div class="flex flex-wrap items-center gap-2 mb-8">
                        <div>
                            <div class="text-sm font-normal" :class="form.is_open ? 'text-white' : 'text-slate-400'">
                                {{ props.weekdays[props.current_weekday] }}
                            </div>
                            <div v-if="form.is_open" class="text-xs font-light text-green-500">
                                aperto
                            </div>
                            <div v-else class="text-xs font-light text-red-500">
                                chiuso
                            </div>
                        </div>

                        <Toggle v-model="form.is_open" :disabled="form.processing" class="mx-4"/>

                        <Select
                            v-model="form.open_start"
                            isArray
                            @change="validations()"
                            :options="props.hours"
                            :disabled="!form.is_open"
                            default="Inizio"
                            :error="form.errors['availability.open_start']"
                            :required="form.is_open"
                            class="w-24"
                        />

                        <Select
                            v-model="form.open_end"
                            isArray
                            @change="validations()"
                            :options="hours.filter(h => h > form.open_start)"
                            default="Fine"
                            :disabled="!form.is_open"
                            :error="form.errors['availability.open_end']"
                            :required="form.is_open"
                            class="w-24"
                        />
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- fasce orarie -->
            <FormElement v-if="form.is_open && form.open_start && form.open_end">
                <template #title>
                    Fasce orarie
                </template>
                <template #description>
                    Imposta le fasce orarie del {{ props.weekdays[form.current_weekday] }}. Ricordati di salvare anche quando elimini delle fasce orarie.<br>
                    Le fasce orarie, se inserite, devono essere almeno due e devono avere nomi univoci nello stesso giorno; possono avere lo stesso nome se in giorni diversi.<br>
                    La fine dell'ultima fascia deve corrispondere all'orario di chiusura dello Studio.

                    <div class="mt-2 text-red-500">
                        <strong>ATTENZIONE:</strong> eliminando le fasce orarie verranno eliminate TUTTE le tariffe delle Sale associate, se presenti.
                    </div>
                </template>
                <template #content>
                    <template v-if="form.timebands.length">
                        <InfoBlock
                            v-show="hasValidationErrors"
                            color="danger"
                            icon="fa-solid fa-exclamation"
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
                                            :options="props.hours.filter(h => h <= form.open_end && h > timeband.start)"
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
                            Non sono presenti fasce orarie per {{ props.weekdays[form.current_weekday] }}.
                        </template>
                        <template #actions>
                            <Button @click="addTimeband()" text="Aggiungi" icon="fa-solid fa-plus" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template #actions>
            <Select v-model.number="form.current_weekday" @change="selectWeekday()" :options="props.weekdays" default="Seleziona giorno" class="w-40" />
            <SaveButton v-if="form.isDirty && !form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
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

const props = defineProps({
    availability: Object,
    timebands: Object,
    all_timebands: Object,
    current_weekday: Number,
    hours: Object,
    weekdays: Object,
});

const hasValidationErrors = ref(false);
const cloneFromWeekday = ref('');

const form = useForm({
    current_weekday: props.current_weekday,
    is_open: props.availability.is_open,
    open_start: props.availability.open_start,
    open_end: props.availability.open_end,
    timebands: props.timebands ?? [],
});

const submit = ()=>{
    if(form.processing) return;
    form.put(route('studio.availability.update'), {
        preserveState: false,
    });
};

const selectWeekday = ()=>{
    router.get(route('studio.availability.edit'), {current_weekday: form.current_weekday}, {
        preserveState: false
    });
};

const cloneAvailability = ()=>{
    router.put(route('studio.availability.clone'), {current_weekday: form.current_weekday, clone_from_weekday: cloneFromWeekday.value}, {
        preserveState: false
    });
};

const addTimeband = ()=>{
    let timeband = {
        id: null,
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
        if(index) timeband.start = form.timebands[index -1].end;
        else timeband.start = form.open_start;
    });
};

const validations = ()=>{
    setTimebandsStartEnd();

    //validazione orari delle fasce orarie
    hasValidationErrors.value = false;
    if(
        form.timebands.length &&
        (form.timebands[0].start !== form.open_start ||
        form.timebands[form.timebands.length -1].end !== form.open_end)
    ){
        hasValidationErrors.value = {
            title: 'Orario inizio/fine non coerente con apertura/chiusura',
            message: 'Gli orari di inizio e fine della prima e ultima fascia oraria devono coincidere con quelle di apertura e chiusura dello Studio.'
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
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>
