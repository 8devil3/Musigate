<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Fasce orarie"
        icon="fa-solid fa-clock"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #title>
                    {{ props.weekdays[form.weekday] }}
                </template>
                <template #description>
                    Imposta le fasce orarie del {{ props.weekdays[form.weekday] }}. Ricordati di salvare anche quando elimini delle fasce orarie.<br>
                    Le fasce orarie, se inserite, devono essere almeno due e devono avere nomi univoci nello stesso giorno ma possono avere lo stesso nome se in giorni diversi.<br>
                    L'inizio della prima fascia deve corrispondere all'orario di apertura dello Studio.<br>
                    La fine dell'ultima fascia deve corrispondere all'orario di chiusura dello Studio.<br><br>

                    <span class="text-red-500">
                        <strong>ATTENZIONE:</strong> eliminando le fasce orarie verranno eliminate anche le tariffe associate delle Sale.
                    </span>
                </template>
                <template #content>
                    <div v-if="props.availability.is_open">
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
                                                :options="props.hours.filter(h => h <= props.availability.open_end && h > timeband.start)"
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
                                <button type="button" v-if="form.timebands[form.timebands.length -1].end !== props.availability.open_end" @click="addTimeband()" class="p-1 text-sm font-semibold text-orange-500 transition-colors hover:text-orange-400 disabled:opacity-50 disabled:cursor-not-allowed">
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
                                Non sono presenti fasce orarie per {{ props.weekdays[form.weekday] }}.
                            </template>
                            <template #actions>
                                <div class="flex flex-col w-40 gap-2 mx-auto text-center">
                                    <Button @click="addTimeband()" text="Aggiungi" icon="fa-solid fa-plus" />
                                    - oppure -
                                    <Select v-if="props.all_timebands" v-model="form.clone_from_weekday" @change="submit()" :options="props.all_timebands" label="Copia da" default="Seleziona giorno" />
                                </div>
                            </template>
                        </Empty>
                    </div>

                    <div v-else>
                        <Empty icon="fa-solid fa-circle-xmark">
                            <template #title>
                                Giorno di chiusura
                            </template>
                        </Empty>
                    </div>
                </template>
            </FormElement>
        </template>

        <template #actions>
            <Select v-model.number="form.weekday" @change="selectWeekday()" :options="props.weekdays" default="Seleziona giorno" class="w-40" />
            <SaveButton v-if="props.availability.is_open && form.isDirty && !form.clone_from_weekday && !hasValidationErrors" :disabled="form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import InfoBlock from '@/Components/InfoBlock.vue';
import Button from '@/Components/Form/Button.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Input from '@/Components/Form/Input.vue';
import Select from '@/Components/Form/Select.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';

const props = defineProps({
    timebands: Object,
    all_timebands: Array,
    availability: Object,
    weekdays: Object,
    hours: Array,
    weekday: Number,
});

const hasValidationErrors = ref(false);

const form = useForm({
    weekday: props.weekday ?? 1,
    clone_from_weekday: '',
    timebands: props.timebands ?? [],
});

const submit = ()=>{
    if(form.processing || hasValidationErrors.value) return;
    form.put(route('studio.timebands.update'), {
        preserveState: false,
        onSuccess: ()=> form.reset(),
    });
};

const selectWeekday = ()=>{
    router.get(route('studio.timebands.edit'), {weekday: form.weekday}, {
        preserveState: false
    });
};

const addTimeband = ()=>{
    let timeband = {
        id: null,
        name: null,
        start: props.availability.open_start,
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
        else timeband.start = props.availability.open_start;
    });
};

const validations = ()=>{
    setTimebandsStartEnd();

    //validazione orari delle fasce orarie
    hasValidationErrors.value = false;
    if(
        form.timebands.length &&
        (form.timebands[0].start !== props.availability.open_start ||
        form.timebands[form.timebands.length -1].end !== props.availability.open_end)
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

router.on('start', ()=> form.processing = true);
router.on('finish', ()=> form.processing = false);

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Fasce orarie',
    }, {default: () => page}),
};
</script>
