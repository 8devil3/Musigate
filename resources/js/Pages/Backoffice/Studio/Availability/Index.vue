<template>
    <ContentLayout
        as="div"
        title="Disponibilità settimanale"
        icon="fa-solid fa-clock"
    >
        <template #content>
            <!-- copia disponibilità -->
            <FormElement>
                <template #description>
                    Click o tap su un giorno della settimana per impostare o modificare gli orari e le fasce orarie.
                </template>
                <template #content>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <Link v-for="av in props.availability" :href="route('studio.availability.edit', av.id)" class="block p-4 space-y-2 font-normal transition-colors border-2 rounded-xl border-slate-600 hover:border-orange-500 hover:bg-slate-900">
                            <div class="text-base" :class="av.is_open ? 'text-white' : 'text-slate-400'">
                                {{ props.weekdays[av.weekday] }}
                                <div class="text-xs" :class="av.is_open ? 'text-green-500' : 'text-red-500'">
                                    {{ av.is_open ? 'aperto' : 'chiuso' }}
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div v-if="!av.timebands.length" class="text-xs text-slate-400">
                                    nessuna fascia oraria
                                </div>
                                <div v-else v-for="timeband in av.timebands" class="text-xs text-white">
                                   {{ timeband.name }} {{ timeband.start }} - {{ timeband.end }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </template>
            </FormElement>
            <!-- / -->
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
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
    if(form.processing || hasValidationErrors.value) return;
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
