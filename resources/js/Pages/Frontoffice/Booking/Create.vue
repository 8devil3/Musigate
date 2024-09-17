<template>
    <form @submit.prevent="submit()" class="flex items-center justify-center w-full h-full p-4 lg:p-6 bg-slate-950">
        <div>
            <Link :href="route('studio.show', props.room.studio_id)" class="block py-4 text-sm text-orange-500 transition-colors hover:text-orange-400">
                <i class="fa-solid fa-arrow-left" />
                Torna allo Studio
            </Link>

            <fieldset class="grid w-full max-w-xl grid-cols-1 gap-4 lg:grid-cols-2 lg:h-[330px]">
                <VueDatePicker
                    v-model="form.date"
                    inline
                    auto-apply
                    model-type="yyyy-MM-dd"
                    :enable-time-picker="false"
                    locale="it"
                    :disabled-week-days="props.closing_weekdays"
                    :max-date="dayjs().add(90, 'days').format('YYYY-MM-DD')"
                    :min-date="dayjs().add(1, 'days').format('YYYY-MM-DD')"
                    @update:model-value="selectedDate()"
                    required
                    dark
                />
    
                <div v-if="props.slots?.length" class="grid w-full pr-4 space-y-2 overflow-y-scroll">
                    <label v-for="slot, index in props.slots" :for="'slot-' + index" class="block w-full p-2 text-sm text-center transition-colors border-2 rounded-full cursor-pointer hover:bg-orange-500/20 hover:border-orange-500 has-[:disabled]:border-red-600 has-[:disabled]:cursor-not-allowed" :class="form.slots.includes(slot) ? 'border-orange-500 bg-orange-500/40 text-white font-semibold' : 'border-slate-400 '">
                        <input type="checkbox" v-model="form.slots" :id="'slot-' + index" :value="slot" :disabled="!slot.is_available" class="hidden" />
                        <div v-if="slot.is_available">
                            {{ slot.start }} - {{ slot.end }}
                        </div>
                        <div v-else class="text-red-600">
                            Occupato
                        </div>
                    </label>
                </div>
            </fieldset>

            <div class="mt-4 text-right">
                <span v-show="!isContiguousSlots" class="pr-4 text-red-500">
                    Attenzione, slot non contigui.
                </span>
                <Button type="submit" text="Prenota" :disabled="!isContiguousSlots || form.processing || !form.slots.length" :isLoading="form.processing" />
            </div>
        </div>
    </form>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import dayjs from "dayjs";

const props = defineProps({
    room: Object,
    slots: Object,
    closing_weekdays: Array,
    request_date: Object,
});

const form = useForm({
    date: props.request_date ?? null,
    slots: []
});

const isContiguousSlots = computed(()=>{
    let check = true;

    if(form.slots.length){
        let prevSlot = null;
        form.slots.forEach(slot => {
            if(prevSlot && (slot.id - prevSlot.id) !== 1) check = false;
            prevSlot = slot;
        });
    }

    return check;
});

const submit = ()=>{
    if(!form.processing && isContiguousSlots.value && form.slots.length){
        form.post(route('booking.store'));
    } else {
        return
    };
};

const selectedDate = ()=>{
    form.get(route('booking.create', props.room.id));
};

</script>
