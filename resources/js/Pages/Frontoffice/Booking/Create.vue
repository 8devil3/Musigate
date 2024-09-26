<template>
    <Head :title="'Prenota ' + props.room.name" />

    <div class="flex items-center justify-center w-full h-full p-4 bg-slate-950 md:p-6">
        <div class="w-full max-w-5xl space-y-6">
            <Link :href="route('studio.show', props.room.studio_id)" class="block text-sm text-orange-500 transition-colors hover:text-orange-400">
                <i class="mr-2 fa-solid fa-arrow-left" />
                Annulla e torna allo Studio
            </Link>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-0">
                <fieldset :disabled="form.processing" class="mr-12 space-y-8 disabled:cursor-not-allowed">
                    <form @submit.prevent="updateSlot()" class="flex flex-wrap items-end gap-2">
                        <Input type="date" v-model="form.startDate" @change="updateSlotSubmitBtn.click()" label="Data" required />
                        <NumberInput v-model="form.duration" @input="updateSlotSubmitBtn.click()" @change="updateSlotSubmitBtn.click()" label="Durata (ore)" :min="1" :max="24" required />
                        <NumberInput v-model="form.guests" label="Artisti" :min="1" :max="props.room.max_capacity" required />
                        <Button @click="reset()" title="Reset" icon="fa-solid fa-arrow-rotate-left" class="hidden lg:inline-flex" />
                        <button ref="updateSlotSubmitBtn" type="submit" hidden />
                    </form>

                    <div v-if="form.processing" class="flex items-center justify-center min-h-[400px]">
                        <Spinner class="w-8 h-8" />
                    </div>

                    <div v-else-if="form.startDate && form.duration && props.slots.length" class="space-y-4">
                        <h4 class="pb-1 mb-4 border-b border-orange-500">Scegli l'orario d'inizio</h4>

                        <ul class="grid grid-cols-2 gap-2 list-none sm:grid-cols-3 list-image-none">
                            <li v-for="slot in props.slots">
                                <button type="button" @click="selectSlot(slot)" class="block w-full px-4 py-2 transition-colors border-2 rounded-full hover:border-orange-500" :class="form.start === slot ? 'bg-orange-900 border-orange-500 font-medium text-white' : 'border-slate-500'">
                                    {{ dayjs(slot).format('HH:mm') }}
                                </button>
                            </li>
                        </ul>

                        <Button @click="reset()" text="Reset" icon="fa-solid fa-arrow-rotate-left" class="w-full lg:hidden" />
                    </div>

                    <p v-else-if="form.startDate && form.duration && !props.slots.length" class="p-4 text-center text-red-500">
                        Nessun orario disponibile per la data e la durata selezionate.
                    </p>

                    <p v-else class="p-4 text-center text-red-500">
                        Scegli la data e durata della sessione e il numero di artisti partecipanti.
                    </p>

                    <ul v-if="Object.keys(form.errors).length" class="text-red-500 list-image-none">
                        <li v-for="error in form.errors">{{ error }}</li>
                    </ul>
                </fieldset>

                <div class="space-y-8 grow">
                    <div class="space-y-6">
                        <div>
                            <div class="text-xs font-semibold uppercase text-slate-400">{{ props.room.studio.category }} studio</div>
                            <h1>{{ props.room.studio.name }}</h1>
                            <h2>{{ props.room.name }}</h2>
                        </div>
                        <Carosello :imgs="roomImgs" class="rounded-xl overflow-clip" />
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- condizioni prenotazione -->
                        <div>
                            <h4 class="pb-1 mb-4 border-b border-orange-500">Condizioni di prenotazione</h4>
                            <ul class="space-y-2 list-musigate">
                                <li class="text-sm list-musigate">
                                    Prenotazione minima <span class="font-semibold text-orange-500">{{ props.booking_settings.min_booking === 1 ? props.booking_settings.min_booking + ' ora' : props.booking_settings.min_booking + ' ore' }}</span>
                                    <!-- <InfoIcon msg="Prenotazione minima" /> -->
                                </li>
                                <li class="text-sm list-musigate">
                                    Preavviso <span class="font-semibold text-orange-500">{{ props.booking_settings.booking_advance === 1 ? props.booking_settings.booking_advance + ' giorno' : props.booking_settings.booking_advance + ' giorni' }}</span>
                                </li>
                                <li class="text-sm list-musigate" v-if="props.booking_settings.has_buffer">
                                    Sono previste pause di 30 min tra le sessioni
                                </li>
                            </ul>
                        </div>
                        <!-- / -->
    
                        <!-- dati prenotazione -->
                        <div v-if="form.start && form.end">
                            <h4 class="pb-1 mb-4 border-b border-orange-500">Dati di prenotazione</h4>
                            <ul class="space-y-2">
                                <li class="text-sm">
                                    <i class="inline-flex justify-center w-5 mr-2 fa-solid fa-calendar-days" />{{ dayjs(form.start).format('DD MMMM YYYY') }}
                                </li>
                                <li class="text-sm">
                                    <i class="inline-flex justify-center w-5 mr-2 fa-solid fa-clock" />{{ dayjs(form.start).format('HH:mm') }} - {{ dayjs(form.end).format('HH:mm') }}
                                </li>
                                <li class="text-sm">
                                    <i class="inline-flex justify-center w-5 mr-2 fa-solid fa-hourglass-half" />{{ bookingDuration === 1 ? bookingDuration + ' ora' : bookingDuration.toString().replace('.', ',') + ' ore' }}
                                </li>
                                <li class="text-sm">
                                    <i class="inline-flex justify-center w-5 mr-2 fa-solid fa-users" />{{ form.guests == 1 ? form.guests + ' artista' : form.guests + ' artisti' }}
                                </li>
                            </ul>
                        </div>
                        <!-- / -->
                    </div>

                    <Button
                        v-if="form.start && form.end && bookingDuration >= props.booking_settings.min_booking"
                        @click="submit()"
                        text="Procedi al pagamento"
                        icon="fa-solid fa-credit-card"
                        color="green" :isLoading="form.processing"
                        :disabled="form.processing"
                        class="w-full"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, useForm, Head, router } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import dayjs from "dayjs";
import duration from 'dayjs/plugin/duration';
import Input from '@/Components/Form/Input.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Spinner from '@/Components/Spinner.vue';
import Carosello from '@/Components/Frontoffice/Carosello.vue';
import InfoIcon from '@/Components/InfoIcon.vue';

dayjs.extend(duration);

const props = defineProps({
    events: Object,
    room: Object,
    slots: Object,
    booking_settings: Object,
    request: Object,
});

const updateSlotSubmitBtn = ref(null);

const form = useForm({
    startDate: props.request?.startDate ?? null,
    start: props.request?.start ?? null,
    duration: props.request?.duration ?? props.booking_settings.min_booking,
    guests: props.request?.guests ?? 1,
});

const bookingDuration = computed(()=>{
    if(form.start && form.end) return dayjs.duration(dayjs(form.end).diff(form.start)).asHours();
});

const updateSlot = ()=>{
    if(form.processing) return;
    form.start = null;
    form.get(route('reservation.create', props.room.id));
};

const selectSlot = (slot)=>{
    if(form.start && slot === form.start){
        form.start = null;
        form.end = null;
    } else {
        form.start = slot;
        if(form.start) form.end = dayjs(form.start).add(form.duration, 'hours').format('YYYY-MM-DD HH:mm');
    }
};

const reset = ()=>{
    router.get(route('reservation.create', props.room.id));
};

const submit = ()=>{
    if(form.processing || !form.start || !form.end) return;
    form.post(route('reservation.store', props.room.id));
};

const roomImgs = computed(()=>{
    let imgs = [];
    
    if(props.room.photos.length){
        imgs = props.room.photos.sort(photo => photo.is_featured ? -1 : 1).map(photo => photo.path).flat();
    }

    return imgs;
});

</script>
