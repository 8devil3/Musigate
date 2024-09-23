<template>
    <FullCalendar ref="fullCalendarRef" :options="calendarOptions" class="text-sm leading-tight" />
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
});

const emits = defineEmits(['eventClick', 'selected', 'unselected']);
const fullCalendarRef = ref(null);

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
    return {
        min: dayjs.min(businessHours.value.filter(item => item.isOpen).map(item => dayjs(dayjs().format('YYYY-MM-DD') + 'T' + item.startTime))).format('HH:mm'),
        max: dayjs.max(businessHours.value.filter(item => item.isOpen).map(item => dayjs(dayjs().format('YYYY-MM-DD') + 'T' + item.endTime))).format('HH:mm'),
    }
});

const calendarOptions = {
    plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
    initialView: 'dayGridMonth',
    slotDuration: props.has_buffer || props.allow_fractional_durations ? '00:30:00' : '01:00:00',
    slotMinTime: minMaxSlotTime.value.min,
    slotMaxTime: minMaxSlotTime.value.max,
    eventBackgroundColor: '#431407',
    eventBorderColor: '#ff6600',
    locale: 'it-IT',
    firstDay: 1, // primo giorno della settimana: lunedì
    events: props.events,
    displayEventTime: true,
    // timeZone: 'Europe/Rome',
    height: 640,
    eventDisplay: 'block',
    allDaySlot: false,
    selectOverlap: false,
    selectMirror: true,
    expandRows: true,
    // hiddenDays: props.availability.filter(item => !item.is_open).map(item => item.weekday),
    // businessHours: businessHours.value,
    selectConstraint: businessHours.value,
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
    dateClick: ()=>{
        fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
    },
    eventClick: (eventClickInfo )=>{
        emits('eventClick', eventClickInfo);
        // fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
    },
    // eventContent: (arg)=>{
    //     if(arg.event.title === 'Pausa') return arg.event.title + ' 30 min';
    //     else return dayjs(arg.event.start).format('HH:mm') + ' - ' + dayjs(arg.event.end).format('HH:mm');
    // },
    selectable: false,
    selectConstraint: businessHours.value,
    selectMinDistance: 5,
    select: (info) =>{
        let minDuration = props.min_booking * 60; // Durata minima in minuti
        let duration = (info.end - info.start) / (1000 * 60); // Calcola la durata della selezione in minuti

        if (dayjs(info.start).isBefore(dayjs())) {
            alert('Non puoi prenotare nel passato!');
            fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
        } else if (duration < minDuration){
            alert('Seleziona almeno ' + minDuration / 60 + ' ore.');
            fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
        } else if (!props.allow_fractional_durations && duration % 60 !== 0){
            alert('Seleziona ore intere.');
            fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
        } else {
            emits('selected', info);
        }
    },
    unselectAuto: false,
    unselect: ()=>{
        emits('unselected');
    }
};

</script>

<style>
.fc-button {
    @apply !rounded-full !px-3 !border-2;
}

.fc-toolbar-title {
    @apply capitalize;
}

.fc-button-group {
    @apply gap-2;
}

.fc-timegrid-slot {
    @apply !py-2 !px-0 h-auto;
}

.fc-event {
    @apply cursor-pointer;
}

.fc-daygrid-event {
    @apply !shadow-none rounded-full py-0.5 px-1.5;
}

.fc-timegrid-event {
    @apply !shadow-none rounded-lg p-1.5;

}

.fc-event-time {
    @apply !font-medium;
}

.fc-col-header-cell {
    @apply bg-slate-800
}

</style>