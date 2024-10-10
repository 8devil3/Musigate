<template>
    <ContentLayout
        as="div"
        :isLoading="form.processing"
        title="Disponibilità settimanale"
        icon="fa-solid fa-clock"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <div class="overflow-x-scroll">
                <div class="grid grid-cols-7 w-[840px] gap-2 pb-6">
                    <button v-for="wd in weekdays" type="button" @click="currentWd = wd.number" :disabled="form.processing" class="flex items-center justify-center h-8 px-4 text-center transition-colors border-2 rounded-full disabled:opacity-50 disabled:cursor-not-allowed hover:border-orange-500 hover:text-white" :class="currentWd === wd.number ? 'border-orange-500 text-white font-normal bg-orange-500/10' : 'text-slate-400 border-slate-400'">
                        {{ wd.name }}
                    </button>
                </div>
            </div>

            <template v-for="wd in weekdays">
                <div v-if="currentWd === wd.number">
                    <Weekday
                        :weekday="wd"
                        :availability="props.availability[wd.number]"
                        :timebands="props.timebands.filter(tb => tb.weekday === wd.number)"
                        :errors="form.errors"
                        @submitted="submit"
                    />
                </div>
            </template>
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Weekday from './Weekday.vue';

const props = defineProps({
    availability: Object,
    timebands: Object,
    is_open_24_7: Boolean,
    weekday: Number,
});

const currentWd = ref(props.weekday);

const form = useForm({
    weekday: null,
    is_open: null,
    open_start: null,
    open_end: null,
    timebands: [],
});

const submit = (e)=>{
    form.weekday = e.weekday;
    form.is_open = e.is_open;
    form.open_start = e.open_start;
    form.open_end = e.open_end;
    form.timebands = e.timebands;

    form.put(route('studio.availability.update'), {
        preserveState: false,
        onSuccess: ()=> form.reset(),
    });
};

const weekdays = [
    {
        name: 'Lunedì',
        number: 1
    },
    {
        name: 'Martedì',
        number: 2
    },
    {
        name: 'Mercoledì',
        number: 3
    },
    {
        name: 'Giovedì',
        number: 4
    },
    {
        name: 'Venerdì',
        number: 5
    },
    {
        name: 'Sabato',
        number: 6
    },
    {
        name: 'Domenica',
        number: 7
    },
];

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>
