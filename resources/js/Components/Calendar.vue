<template>
    <FullCalendar ref="fullCalendarRef" :options="calendarOptions" />
</template>

<script setup>
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
// import dayGridPlugin from '@fullcalendar/daygrid';
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

const emits = defineEmits(['selected', 'unselected']);
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
    plugins: [ timeGridPlugin, interactionPlugin ],
    initialView: 'timeGridWeek',
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
    height: 600,
    eventDisplay: 'block',
    allDaySlot: false,
    selectOverlap: false,
    selectMirror: true,
    displayEventTime: false,
    hiddenDays: props.availability.filter(item => !item.is_open).map(item => item.weekday),
    businessHours: businessHours.value,
    selectConstraint: businessHours.value,
    slotLabelFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: false,
    },
    // headerToolbar: {
        // right: 'today prev,next',
        // center: 'title',
        // left: 'dayGridMonth,timeGridWeek', // buttons for switching between views
    // },
    validRange: {
        start: dayjs().startOf('week').format('YYYY-MM-DD'),
        end: dayjs().add(props.maxBookingHorizon, 'days').format('YYYY-MM-DD'),
    },
    buttonText: {
        today: 'Oggi',
        month: 'Mese',
        week: 'Settimana',
        day: 'Giorno',
    },
    dateClick: ()=>{
        fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
    },
    eventClick: ()=>{
        fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
    },
    eventContent: (arg)=>{
        if(arg.event.title === 'Pausa') return arg.event.title + ' 30 min';
        else return dayjs(arg.event.start).format('HH:mm') + ' - ' + dayjs(arg.event.end).format('HH:mm');
    },
    selectable: true,
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
.fc-timegrid-slot {
    padding: 8px 0 !important;
    height: auto;
}

.fc-timegrid-event {
    box-shadow: none !important;
}

.fc-timegrid-event {
    padding: 3px 6px;
    border-radius: 12px;
}

</style>