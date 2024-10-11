<template>
    <ContentLayout
        as="div"
        @submitted="submit()"
        title="Prenotazioni"
        icon="fa-solid fa-calendar-days"
    >
        <template #content>
            <Calendar
                v-if="props.rooms"
                :events="props.events"
                :availability="props.availability"
                :has_buffer="props.booking_settings.has_buffer"
                :allow_fractional_durations="props.booking_settings.allow_fractional_durations"
                :min_booking="props.booking_settings.min_booking"
                :maxBookingHorizon="props.booking_settings.maxBookingHorizon"
                :initialView="props.booking_settings.default_calendar_view"
                :is_open_24_7="props.is_open_24_7"
                @eventClick="openModalEvent"
            />

            <div v-else class="flex flex-col items-center h-full gap-4 mt-16 text-center">
                <p class="text-xl font-medium text-slate-500 max-w-80">
                    Devi creare almeno una Sala prove per visualizzare le prenotazioni.
                </p>
                <Button type="router" :href="route('rooms.create')" text="Crea una Sala" icon="fa-solid fa-plus" />
            </div>
        </template>

        <template #actions>
            <Select v-if="props.rooms" v-model="currentRoomId" @change="refresh()" :options="props.rooms" default="Seleziona una Sala" class="w-48" />
        </template>
    </ContentLayout>

    <Modal :isOpen="isOpenModalEvent" @close="closeModalEvent()">
        <template #title>
            <div class="flex items-center gap-3">
                <span class="inline-block size-6 rounded-full shadow-inner" :style="'background-color:' + currentEvent?.event.borderColor" />
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
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Calendar from '@/Components/Calendar.vue';
import Select from '@/Components/Form/Select.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    events: Object,
    availability: Object,
    booking_settings: Object,
    is_open_24_7: Boolean,
    rooms: Object,
    request: Object,
});

const isOpenModalEvent = ref(false);
const currentRoomId = ref(props.request?.room_id ?? 'all');
const currentEvent = ref(null);

const refresh = ()=>{
    router.get(route('bookings.index'), {room_id: currentRoomId.value});
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

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';
import Button from '@/Components/Form/Button.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Prenotazioni',
    }, {default: () => page}),
};
</script>
