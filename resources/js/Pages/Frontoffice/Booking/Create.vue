<template>
    <Head :title="'Prenota ' + props.room.name" />

    <div class="flex items-center justify-center w-full h-full p-4 bg-slate-950 md:p-6">
        <div class="max-w-[1536px] w-full space-y-6">
            <Link :href="route('studio.show', props.room.studio_id)" class="block text-sm text-orange-500 transition-colors hover:text-orange-400">
                <i class="mr-2 fa-solid fa-arrow-left" />
                Annulla e torna allo Studio
            </Link>

            <div class="flex w-full gap-12">
                <Calendar
                    :events="props.events"
                    :availability="props.availability"
                    :has_buffer="props.booking_settings.has_buffer"
                    :allow_fractional_durations="props.booking_settings.allow_fractional_durations"
                    :min_booking="props.booking_settings.min_booking"
                    :maxBookingHorizon="props.booking_settings.maxBookingHorizon"
                    @selected="selectedDateTime"
                    @unselected="form.reset()"
                    class="grow"
                />

                <form @submit.prevent="submit()" class="w-2/5 space-y-4 shrink-0">
                    <h1>{{ props.room.studio.name }}</h1>
                    <h2>{{ props.room.name }}</h2>

                    <div>
                        Prenotazione minima {{ props.booking_settings.min_booking === 1 ? props.booking_settings.min_booking + ' ora' : props.booking_settings.min_booking + ' ore' }}
                    </div>

                    <div v-if="form.start && form.end">
                        Data {{ dayjs(form.start).format('DD MMMM YYYY') }}<br>
                        Dalle ore {{ dayjs(form.start).format('HH:mm') }} alle ore {{ dayjs(form.end).format('HH:mm') }}<br>
                        Durata {{ dayjs.duration(dayjs(form.end).diff(form.start)).asHours() === 1 ? dayjs.duration(dayjs(form.end).diff(form.start)).asHours() + ' ora' : dayjs.duration(dayjs(form.end).diff(form.start)).asHours().toString().replace('.', ',') + ' ore' }}
                    </div>

                    <Button type="submit" v-if="form.start && form.end && form.duration >= props.booking_settings.min_booking" text="Procedi al pagamento" icon="fa-solid fa-credit-card" />
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, Head } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import Calendar from '@/Components/Calendar.vue';
import dayjs from "dayjs";
import duration from 'dayjs/plugin/duration';

dayjs.extend(duration);

const props = defineProps({
    events: Object,
    room: Object,
    availability: Object,
    booking_settings: Object,
    request: Object,
});

const form = useForm({
    start: null,
    end: null,
    duration: null,
});

const selectedDateTime = (e)=>{
    if(e.start && e.end){
        form.start = e.start;
        form.end = e.end;
        form.duration = dayjs(form.end).diff(form.start, 'hours');
    }
};

const submit = ()=>{
    if(form.processing || !form.start || !form.start) return;
    form.post(route('booking.store'));
};

</script>
