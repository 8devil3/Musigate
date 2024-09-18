<template>
    <Head :title="'Prenota ' + props.room.name" />

    <form @submit.prevent="submit()" class="flex items-center justify-center w-full h-full p-4 lg:p-6 bg-slate-950">
        <fieldset :disabled="form.processing" class="space-y-4">
            <Link :href="route('studio.show', props.room.studio_id)" class="block text-sm text-orange-500 transition-colors hover:text-orange-400">
                <i class="mr-2 fa-solid fa-arrow-left" />
                Torna allo Studio
            </Link>

            <div class="grid w-full grid-cols-2 gap-4 text-sm text-center">
                <div>
                    Prenotazione minima {{ props.booking_settings.min_booking }} ore
                </div>
    
                <div class="pr-8 mb-2">
                    Seleziona gli slot orari
                </div>
            </div>

            <div class="grid w-full max-w-xl grid-cols-1 gap-4 lg:grid-cols-2 lg:h-[330px]">
                <VueDatePicker
                    v-model="form.date"
                    inline
                    auto-apply
                    model-type="yyyy-MM-dd"
                    :enable-time-picker="false"
                    locale="it"
                    :disabled-week-days="props.closing_weekdays"
                    :max-date="dayjs().add(props.booking_settings.max_booking_horizon, 'days').format('YYYY-MM-DD')"
                    :min-date="dayjs().add(props.booking_settings.booking_advance, 'days').format('YYYY-MM-DD')"
                    @update:model-value="selectedDate()"
                    :disabled="form.processing"
                    required
                    dark
                />

                <div v-if="props.slots?.length" class="grid w-full h-full pr-4 space-y-2 overflow-y-scroll">
                    <label v-for="slot, index in props.slots" :for="'slot-' + index" class="block w-full p-1.5 text-sm text-center transition-colors border-2 rounded-full cursor-pointer hover:bg-orange-500/20 hover:border-orange-500 has-[:disabled]:border-red-600 has-[:disabled]:text-red-600 has-[:disabled]:cursor-not-allowed" :class="form.slots.includes(slot) ? 'border-orange-500 bg-orange-500/40 text-white font-semibold' : 'border-slate-400 '">
                        <input type="checkbox" v-model="form.slots" :id="'slot-' + index" :value="slot" :disabled="!slot.is_available" class="hidden" />
                        <template v-if="slot.is_available">
                            {{ slot.start }} - {{ slot.end }}
                        </template>
                        <template v-else>
                            Occupato
                        </template>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end h-8 gap-4 text-right">
                <div v-show="!checkSlotMinBooking || !checkContiguitySlots" class="text-sm text-red-500">
                    <template v-if="!checkSlotMinBooking">
                        Attenzione, seleziona minimo {{ props.booking_settings.min_booking }} slot
                    </template>
                    <template v-if="!checkContiguitySlots">
                        Attenzione, slot non contigui!
                    </template>
                </div>

                <div v-if="form.date && form.slots.length && checkSlotMinBooking && checkContiguitySlots" class="text-sm text-green-500">
                    {{ dayjs(form.date).format('dddd DD MMMM YYYY') }} ore {{ form.slots[0].start }}<br>
                    durata di {{ form.slots.length === 1 ? form.slots.length + ' ora' : form.slots.length + ' ore' }}
                </div>

                <Button type="submit" text="Prenota" :disabled="!checkSlotMinBooking || !checkContiguitySlots || form.processing || !form.slots.length" :isLoading="form.processing" />
            </div>
        </fieldset>
    </form>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm, Head } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import dayjs from "dayjs";

const props = defineProps({
    room: Object,
    slots: Object,
    booking_settings: Object,
    closing_weekdays: Array,
    request_date: String,
});

const form = useForm({
    date: props.request_date ?? null,
    slots: []
});

const checkSlotMinBooking = computed(()=>{
    let check = true

    if(form.slots.length && form.slots.length < props.booking_settings.min_booking){
        check = false;
    }
    return check;
});

const checkContiguitySlots = computed(()=>{
    let check = true
    
    if(form.slots.length){
        let prevSlot = null;
        form.slots.sort((a,b) => a.id - b.id).forEach(slot => {
            if(prevSlot && (slot.id - prevSlot.id) !== 1) check = false;
            prevSlot = slot;
        });
    }

    return check;
});

const submit = ()=>{
    if(!form.processing || !form.date || checkContiguitySlots.value || checkSlotMinBooking.value || form.slots.length) return;
    form.post(route('booking.store'));
};

const selectedDate = ()=>{
    form.get(route('booking.create', props.room.id));
};

</script>
