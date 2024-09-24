<template>
<BackofficeLayout
    as="div"
    @submitted="submit()"
    title="Prenotazioni"
    icon="fa-solid fa-calendar-days"
>
    <template #content>
        <Calendar
            :events="props.events"
            :availability="props.availability"
            :has_buffer="props.booking_settings.has_buffer"
            :allow_fractional_durations="props.booking_settings.allow_fractional_durations"
            :min_booking="props.booking_settings.min_booking"
            :maxBookingHorizon="props.booking_settings.maxBookingHorizon"
            :initialView="props.booking_settings.default_calendar_view"
            @eventClick="openModalEvent"
        />
    </template>

    <template #actions>
        <Select v-model="currentRoomId" @change="refresh()" :options="props.rooms" default="Seleziona una Sala" class="w-48" />
    </template>
</BackofficeLayout>

<Modal :isOpen="isOpenModalEvent" @close="closeModalEvent()">
    <template #title>
        <div class="flex items-center gap-3">
            <span class="inline-block w-6 h-6 rounded-full shadow-inner" :style="'background-color:' + currentEvent?.event.borderColor" />
            {{ currentEvent?.event.title }}
        </div>
    </template>

    <template #description>
        <div v-if="currentEvent" class="space-y-4">
            <p v-if="!currentEvent.event.extendedProps.is_imported">
                <i class="inline-flex justify-center w-5 mr-3 fa-solid fa-user" />
                {{ currentEvent.event.extendedProps.user.first_name }} {{ currentEvent.event.extendedProps.user.last_name }}
            </p>

            <p>
                <i class="inline-flex justify-center w-5 mr-3 fa-solid fa-calendar" />
                {{ dayjs(currentEvent.event.start).format('DD MMMM YYYY') }}
            </p>

            <p>
                <i class="inline-flex justify-center w-5 mr-3 fa-solid fa-clock" />
                {{ dayjs(currentEvent.event.start).format('HH:mm') }}
                -
                {{ dayjs(currentEvent.event.end).format('HH:mm') }}
            </p>

            <template v-if="!currentEvent.event.extendedProps.is_imported">
                <p>
                    <i class="inline-flex justify-center w-5 mr-3 fa-solid fa-hourglass-half" />
                    {{ currentEvent.event.extendedProps.dur === 1 ? currentEvent.event.extendedProps.dur + ' ora' : currentEvent.event.extendedProps.dur + ' ore' }}
                </p>
    
                <p>
                    <i class="inline-flex justify-center w-5 mr-3 fa-solid fa-users" />
                    {{ currentEvent.event.extendedProps.guests === 1 ? currentEvent.event.extendedProps.guests + ' artista' : currentEvent.event.extendedProps.guests + ' artisti' }}
                </p>
            </template>
            <template v-else>
                <p>
                    <i class="inline-flex justify-center w-5 mr-3 fa-brands fa-google" />
                    Questo evento è importato da Google Calendar
                </p>
            </template>
        </div>
    </template>
</Modal>

</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import Calendar from '@/Components/Calendar.vue';
import Select from '@/Components/Form/Select.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    events: Object,
    availability: Object,
    booking_settings: Object,
    rooms: Object,
    request: Object,
});

const isOpenModalEvent = ref(false);
const currentRoomId = ref(props.request?.room_id ?? 1000);
const currentEvent = ref(null);

const refresh = ()=>{
    if(currentRoomId.value === 1000) router.get(route('bookings.index'));
    else router.get(route('bookings.index'), {room_id: currentRoomId.value});
};

const openModalEvent = (e)=>{
    currentEvent.value = e;
    isOpenModalEvent.value = true;
};

const closeModalEvent = ()=>{
    isOpenModalEvent.value = false;
    currentEvent.value = null;
};

</script>
