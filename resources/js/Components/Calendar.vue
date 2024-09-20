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
    slotDuration: '01:00:00',
    slotMinTime: minMaxSlotTime.value.min,
    slotMaxTime: minMaxSlotTime.value.max,
    locale: 'it-IT',
    firstDay: 1, // primo giorno della settimana: lunedì
    events: props.events,
    height: 480,
    eventDisplay: 'block',
    allDaySlot: false,
    selectOverlap: false,
    selectMirror: true,
    timeZone: 'Europe/Rome',
    hiddenDays: props.availability.filter(item => !item.is_open).map(item => item.weekday),
    businessHours: businessHours.value,
    selectConstraint: businessHours.value,
    slotLabelFormat: {
        hour: 'numeric',
        minute: '2-digit',
        omitZeroMinute: false,
    },
    headerToolbar: {
        // right: 'today prev,next',
        // center: 'title',
        // left: 'dayGridMonth,timeGridWeek', // buttons for switching between views
    },
    buttonText: {
        today: 'Oggi',
        month: 'Mese',
        week: 'Settimana',
        day: 'Giorno',
    },
    dateClick: function(){
        fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
    },
    selectable: true,
    select: function(info) {
        let minDuration = 3; // Durata minima in ore
        let duration = (info.end - info.start) / (1000 * 3600); // Calcola la durata della selezione in ore

        if (duration < minDuration) {
            alert('Seleziona almeno ' + minDuration + ' ore.');
            fullCalendarRef.value.getApi().unselect(); // Annulla la selezione
        } else {
            emits('selected', info);
        }
    },
    unselectAuto: false,
    unselect: function(){
        emits('unselected');
    }
};

</script>
