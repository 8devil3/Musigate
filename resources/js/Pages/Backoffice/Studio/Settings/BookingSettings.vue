<template>
    <BackofficeLayout
        @submitted="submit()"
        title="Impostazioni prenotazioni"
        icon="fa-solid fa-gears"
        :backRoute="route('studio.links')"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        :tabLinks="tabLinks"
    >
        <template #content>
            <!-- vista calendario -->
            <FormElement>
                <template #title>
                    Vista calendario predefinita
                </template>

                <template #description>
                    Seleziona la vista predefinita del calendario delle prenotazioni.
                </template>

                <template #content>
                    <div class="flex items-center gap-3">
                        <Select v-model.number="form.default_calendar_view" :options="calendarViews" default="Seleziona vista" :error="form.errors.default_calendar_view" required class="w-full max-w-xs" />
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- prenotazione minima -->
            <FormElement>
                <template #title>
                    Prenotazione minima
                </template>

                <template #description>
                    Inserisci la durata minima di prenotazione, espressa in ore.
                </template>

                <template #content>
                    <div class="flex items-center gap-3">
                        <NumberInput v-model.number="form.min_booking" :error="form.errors.min_booking" :min="1" :max="8" required class="w-32" />
                        {{ form.min_booking === 1 ? 'ora' : 'ore' }}
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- anticipo -->
            <FormElement>
                <template #title>
                    Preavviso
                </template>

                <template #description>
                    Inserisci il periodo minimo di preavviso per prenotare, espresso in giorni.<br>
                    Esempio: 1 giorno indica che un artista deve prenotare minimo un giorno prima della sessione.
                </template>

                <template #content>
                    <div class="flex items-center gap-3">
                        <NumberInput v-model.number="form.booking_advance" :error="form.errors.booking_advance" :min="0" :max="form.max_booking_horizon -1" required class="w-32" />
                        {{ form.booking_advance === 1 ? 'giorno' : 'giorni' }}
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- periodo max prenotazione -->
            <FormElement>
                <template #title>
                    Finestra temporale
                </template>

                <template #description>
                    Inserisci il periodo massimo futuro di prenotazione, espresso in giorni. Rappresenta la finestra temporale in cui un artista può prenotare.<br>
                    Esempio: 60 giorni indica che un artista può prenotare fino al sessantesimo giorno a partire da oggi.
                </template>

                <template #content>
                    <div class="flex items-center gap-3">
                        <NumberInput v-model.number="form.max_booking_horizon" :error="form.errors.max_booking_horizon" :min="7" :max="365" required class="w-32" />
                        giorni
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- buffer -->
            <FormElement>
                <template #title>
                    Pause
                </template>

                <template #description>
                    Abilita/disabilita le pause di 30 minuti tra le sessioni.<br>
                    Se abilitata, verrà attivata automaticamente anche l'impostazione <strong>Durate frazionate</strong>
                </template>

                <template #content>
                    <Toggle v-model="form.has_buffer" @click="forceAllowFractionlDurations()" :label="form.has_buffer ? 'Pause abilitate' : 'Pause disabilitate'" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- durate frazionate -->
            <FormElement>
                <template #title>
                    Durate frazionate
                </template>

                <template #description>
                    Abilita/disabilita la possibilità di prenotare durate frazionate di 30 minuti, per esempio 2,5 ore dalle 14:00 alle 16:30.
                </template>

                <template #content>
                    <Toggle v-model="form.allow_fractional_durations" :label="form.allow_fractional_durations ? 'Durate frazionate abilitate' : 'Durate frazionate disabilitate'" :disabled="form.has_buffer" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- Sincronizzazione calendario Google -->
            <FormElement>
                <template #title>
                    Sincronizzazione calendario Google
                </template>

                <template #description>
                    Abilita/disablita la sincronizzazione con un calendario Google. Abilitandola potrai scegliere tra due modalità: <strong>unidirezionale</strong> o <strong>bidirezionale</strong>.<br><br>
                    Se <strong>unidirezionale</strong> Musigate scaricherà semplicamente gli eventi dal calendario prescelto.<br><br>
                    Se <strong>bidirezionale</strong> Musigate, oltra a scaricare gli event, potrà anche crearli nel calendario prescelto.<br><br>
                    Per abilitare la sincronizzazione devi affettuare l'accesso con Google.
                </template>

                <template #content>
                    <div class="flex flex-col gap-4">
                        <div class="space-y-2">
                            <Toggle v-model="form.has_sync" :disabled="!props.google_token" :label="form.has_sync ? 'Sincronizzazione abilitata' : 'Sincronizzazione disabilitata'" />

                            <div v-if="!props.google_token" class="text-sm text-red-500">
                                Devi accedere con Google per abilitare la sincronizzazione.<br>
                                Effettua il log out e accedi con Google.
                            </div>
                        </div>

                        <template v-if="form.has_sync && props.google_token">
                            <Select v-model="form.sync_mode" isArray :options="['unidirezionale', 'bidirezionale']" default="Seleziona modalità" class="w-full max-w-xs" />
                            <Select v-model="form.google_calendar_id" isArray :options="props.google_calendar_ids" default="Seleziona un calendario" class="w-full max-w-xs" />
                        </template>
                    </div>
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template #actions>
            <SaveButton />
        </template>
    </BackofficeLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';

const props = defineProps({
    booking_settings: Object,
    google_calendar_ids: Array,
    google_token: String,
})

const form = useForm({
    min_booking: props.booking_settings.min_booking,
    booking_advance: props.booking_settings.booking_advance,
    max_booking_horizon: props.booking_settings.max_booking_horizon,
    has_buffer: props.booking_settings.has_buffer,
    buffer: props.booking_settings.buffer,
    allow_fractional_durations: props.booking_settings.allow_fractional_durations,
    has_sync: props.booking_settings.has_sync,
    sync_mode: props.booking_settings.sync_mode ?? '',
    google_calendar_id: props.booking_settings.google_calendar_id ?? '',
    default_calendar_view: props.booking_settings.default_calendar_view ?? 'dayGridMonth'
});

const forceAllowFractionlDurations = ()=>{
    if(form.has_buffer) form.allow_fractional_durations = true;
};

const submit = () => {
    if(form.processing) return;

    if(form.has_buffer) form.allow_fractional_durations = true;

    form.put(route('bookings.settings.update'));
};

const calendarViews = {
    dayGridMonth: 'Mensile',
    timeGridWeek: 'Settimanale',
};

const tabLinks = [
    {
        name: 'Prenotazioni',
        href: route('bookings.settings.edit'),
        active: route().current('bookings.settings.edit')
    },
    {
        name: 'Annullamenti',
        href: route('cancelling.settings.edit'),
        active: route().current('cancelling.settings.edit')
    },
];

</script>
