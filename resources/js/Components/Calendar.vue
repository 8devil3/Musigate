<template>
<FullCalendar ref="fullCalendarRef" :options="calendarOptions" class="h-full text-sm leading-tight">
    <template #eventContent='arg'>
        <div v-if="arg.view.type === 'timeGridWeek'" :title="eventHoverTitle(arg)" class="p-1.5 space-y-2 w-full">
            <div>
                {{  dayjs(arg.event.start).format('HH:mm') }}
                -
                {{  dayjs(arg.event.end).format('HH:mm') }}
            </div>

            <div class="w-full font-semibold truncate">{{ arg.event.title }}</div>

            <div v-if="!arg.event.extendedProps.is_imported" class="flex w-full gap-1 truncate">
                <i class="fa-solid fa-user" />
                {{ arg.event.extendedProps.user.first_name }}
                {{ arg.event.extendedProps.user.last_name }}
            </div>

            <div v-else class="w-full truncate">
                <i class="mr-0.5 fa-brands fa-google" />
                Importato
            </div>

            <template v-if="arg.event.extendedProps.has_buffer">
                <div class="absolute bottom-0 left-0 w-full italic truncate px-1.5 py-2">
                    Pausa 30 min
                </div>
            </template>
        </div>

        <template v-else-if="arg.view.type === 'dayGridMonth'">
            <div class="flex items-center w-full gap-1" :title="eventHoverTitle(arg)">
                <span class="inline-block w-2 h-2 rounded-full shrink-0" :style="'background-color:' + arg.event.borderColor" />
                <span class="shrink-0">
                    {{ dayjs(arg.event.start).format('HH:mm') }}
                </span>
                <div class="w-full font-semibold truncate">
                    {{ arg.event.extendedProps.user.first_name }}
                    {{ arg.event.extendedProps.user.last_name }}
                </div>
            </div>
        </template>
    </template>
</FullCalendar>
</template>

<script setup>
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import dayjs from 'dayjs';
import minMax  from 'dayjs/plugin/minMax';

dayjs.extend(minMax);

const props = defineProps({
    events: Object,
    availability: Object,
    min_booking: Number,
    has_buffer: Boolean,
    allow_fractional_durations: Boolean,
    maxBookingHorizon: Number,
    is_open_24_7: Boolean,
    initialView: String,
});

const emits = defineEmits(['eventClick', 'selected', 'unselected']);
const fullCalendarRef = ref(null);

const eventHoverTitle = (arg)=>{
    return dayjs(arg.event.start).format('HH:mm') + ' - ' + dayjs(arg.event.end).format('HH:mm') + ' | ' + arg.event.title;
};

const businessHours = computed(()=>{
    return props.availability.map(item => {
        return {
            daysOfWeek: [item.weekday],
            startTime: item.start,
            endTime: item.end,
            isOpen: item.is_open,
        }
    });
});

const minMaxSlotTime = computed(()=>{
    let bh = businessHours.value.filter(item => item.isOpen);

    if(!bh.length || props.is_open_24_7){
        return {
            min: '00:00',
            max: '24:00',
        };
    } else {
        return {
            min: dayjs.min(businessHours.value.filter(item => item.isOpen).map(item => dayjs(dayjs().format('YYYY-MM-DD') + 'T' + item.startTime))).format('HH:mm'),
            max: dayjs.max(businessHours.value.filter(item => item.isOpen).map(item => dayjs(dayjs().format('YYYY-MM-DD') + 'T' + item.endTime))).format('HH:mm'),
        }
    }
});

const calendarOptions = {
    plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
    initialView: props.initialView,
    slotDuration: props.has_buffer || props.allow_fractional_durations ? '00:30:00' : '01:00:00',
    slotMinTime: minMaxSlotTime.value.min,
    slotMaxTime: minMaxSlotTime.value.max,
    eventBackgroundColor: '#431407',
    eventBorderColor: '#ff6600',
    locale: 'it-IT',
    firstDay: 1, // primo giorno della settimana: 1 -> lunedì
    events: props.events,
    displayEventTime: true,
    eventDisplay: 'auto',
    allDaySlot: false,
    expandRows: true,
    eventTimeFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: false,
    },
    slotLabelFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: false,
        meridiem: false,
    },
    views: {
        dayGrid: {
            // options apply to dayGridMonth, dayGridWeek, and dayGridDay views
            dayHeaderFormat: {
                weekday: 'short',
                omitCommas: true,
            },
        },
        timeGrid: {
            // options apply to timeGridWeek and timeGridDay views
            dayHeaderFormat: {
                weekday: 'short',
                day: 'numeric',
                omitCommas: true,
            },
        },
    },
    titleFormat: {
        year: 'numeric',
        month: 'long'
    },
    headerToolbar: {
        right: 'today prev,next',
        center: 'title',
        left: 'dayGridMonth,timeGridWeek',
    },
    // validRange: {
    //     start: dayjs().startOf('week').format('YYYY-MM-DD'),
    //     end: dayjs().add(props.maxBookingHorizon, 'days').format('YYYY-MM-DD'),
    // },
    buttonText: {
        today: 'Oggi',
        month: 'Mese',
        week: 'Settimana',
        day: 'Giorno',
    },
    eventClick: (eventClickInfo )=>{
        emits('eventClick', eventClickInfo);
    },
    selectable: false,
};

</script>

<style>
.fc-button {
    @apply !rounded-full !px-3 !border-2;
}

.fc-toolbar-title {
    @apply capitalize !text-base xl:!text-2xl !text-center;
}

.fc-button-group {
    @apply gap-2;
}

.fc-timegrid-slot {
    @apply !py-3 !px-0 h-auto;
}

.fc-event {
    @apply cursor-pointer;
}

.fc-daygrid-event {
    @apply rounded-full py-0.5 px-1.5;
}

.fc-timegrid-event {
    @apply !shadow rounded-lg p-0;

}

.fc-event-time {
    @apply !font-medium;
}

.fc-col-header-cell {
    @apply bg-slate-800
}

</style>