<template>
    <form @submit.prevent="submit()">
        <fieldset :disabled="form.processing">
            <FormElement>
                <template #title>
                    {{ props.weekday.name }}
                </template>
                <template #description>
                    Imposta gli orari di apertura/chiusura dello Studio e le fasce orarie del {{ props.weekday.name }}. Ricordati di salvare anche quando elimini delle fasce orarie.<br>
                    Le fasce orarie, se inserite, devono essere almeno due e devono avere nomi univoci nello stesso giorno ma possono avere lo stesso nome se in giorni diversi.<br>
                    L'inizio della prima fascia deve corrispondere all'orario di apertura dello Studio.<br>
                    La fine dell'ultima fascia deve corrispondere all'orario di chiusura dello Studio.
                    <div class="mt-4">
                        <SaveButton :disabled="hasValidationError ? true : false" />
                    </div>
                </template>
                <template #content>
                    <div class="pb-4 space-y-6">
                        <!-- orari apertura -->
                        <div>
                            <h4 class="mb-4 tet-base text-slate-200">Orari di apertura</h4>

                            <div class="text-sm font-normal text-red-500">
                                {{ form.error }}
                            </div>
        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ props.weekday.name }}</th>
                                        <th class="ptext-center">dalle ore</th>
                                        <th class="text-center">alle ore</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="w-40 text-center">
                                            <Toggle
                                                v-model="form.is_open"
                                                :disabled="form.processing"
                                                :label="form.is_open ? 'Aperto' : 'Chiuso'"
                                            />
                                        </td>
                                        <td class="text-center">
                                            <Select
                                                v-model="form.open_start"
                                                @change="validations()"
                                                isArray
                                                :options="hours"
                                                :disabled="!form.is_open"
                                                :error="props.errors.open_start":required="form.is_open"
                                                class="w-24"
                                            />
                                        </td>
                                        <td class="text-center">
                                            <Select
                                                v-model="form.open_end"
                                                @change="validations()"
                                                isArray
                                                :options="hours.filter(h => h > form.open_start)"
                                                :disabled="!form.is_open"
                                                :error="props.errors.open_end":required="form.is_open"
                                                class="w-24"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- / -->

                        <!-- fasce orarie -->
                        <div v-if="form.is_open && form.open_start && form.open_end">
                            <hr class="h-0 mb-6 border-t border-slate-700" />

                            <h4 class="mb-4 tet-base text-slate-200">Fasce orarie</h4>

                            <template v-if="form.timebands.length">
                                <InfoBlock
                                    v-show="hasValidationError"
                                    color="danger"
                                    icon="fa-solid fa-circle-exclamation"
                                    :title="hasValidationError.title"
                                    class="mb-4"
                                >
                                    {{ hasValidationError.message }}
                                </InfoBlock>

                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">nome fascia</th>
                                            <th class="text-center">inizio</th>
                                            <th class="text-center">fine</th>
                                            <th class="text-center">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td class="pt-1"></td></tr>
                                        <tr v-for="timeband, index in form.timebands">
                                            <td class="w-full text-center min-w-32">
                                                <Input v-model="timeband.name" @change="validations()" placeholder="Nome fascia oraria" autofocus required />
                                            </td>
                                            <td class="text-center">
                                                <Select
                                                    v-model="timeband.start"
                                                    @change="validations()"
                                                    isArray
                                                    :options="hours.filter(h => h >= form.open_start && h < form.open_end)"
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
                                                    :options="hours.filter(h => h <= form.open_end && h > timeband.start)"
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
                                    Non sono presenti fasce orarie per il {{ props.weekday.name }}.
                                </template>
                                <template #actions>
                                    <Button @click="addTimeband()" text="Aggiungi" icon="fa-solid fa-plus" />
                                </template>
                            </Empty>
                        </div>
                        <!-- / -->
                    </div>
                </template>
            </FormElement>
        </fieldset>
    </form>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Input from '@/Components/Form/Input.vue';
import Button from '@/Components/Form/Button.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import dayjs from 'dayjs';

const props = defineProps({
    availability: Object,
    timebands: Object,
    weekday: Object,
    errors: Object,
});

const emit = defineEmits(['isLoading', 'success', 'error', 'submitted']);
const hasValidationError = ref(false);

const form = useForm({
    weekday: props.weekday.number,
    is_open: props.availability?.is_open ?? false,
    open_start: props.availability?.open_start ?? '',
    open_end: props.availability?.open_end ?? '',
    timebands: props.timebands ?? [],
});

const submit = () => {
    if(form.processing || hasValidationError.value) return;
    emit('submitted', form);
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

const deleteTimeband = (index, id)=>{
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

    //validazione orari apertura chiusura
    form.errors.open_start = null;
    form.errors.open_end = null;
    if(form.open_start && form.open_end){
        if(form.open_end <= form.open_start){
            form.errors.open_start = 'Non valido';
            form.errors.open_end = 'Non valido';
        }
    }

    //validazione orari delle fasce orarie
    hasValidationError.value = false;
    if(
        form.timebands.length &&
        (form.timebands[0].start !== form.open_start ||
        form.timebands[form.timebands.length -1].end !== form.open_end)
    ){
        hasValidationError.value = {
            title: 'Orario inizio/fine non coerente con apertura/chiusura',
            message: 'Gli orari di inizio e fine della prima e ultima fascia oraria devono coincidere con quelle di apertura e chiusura dello Studio.'
        };
    }

    //validazione numero di fasce orarie (minimo 2)
    if(form.timebands.length && form.timebands.length < 2){
        hasValidationError.value = {
            title: 'Fascia oraria singola',
            message: 'Devi inserire almeno due fasce orarie.'
        };
    }

    //validazione nomi univoci
    let timebandNames = form.timebands.map(tb => tb.name);
    let hasDuplicates = new Set(timebandNames).size !== timebandNames.length;
    
    if(hasDuplicates){
        hasValidationError.value = {
            title: 'Nome fascia non univoco',
            message: 'Le fasce orarie devono avere nomi univoci nello stesso giorno. Possono avere lo stesso nome in giorni diversi.'
        };
    }
};

const hours = computed(()=>{
    let arrHours = [];

    for (let i = 0; i < 24; i++) {
        if(i < 10){
            arrHours.push('0' + i + ':00');
            arrHours.push('0' + i + ':30')
        }
        else{
            arrHours.push(i + ':00');
            arrHours.push(i + ':30');
        }
    }

    arrHours.push('24:00');

    return arrHours;
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';
import InfoBlock from '@/Components/InfoBlock.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità',
    }, {default: () => page}),
};
</script>
