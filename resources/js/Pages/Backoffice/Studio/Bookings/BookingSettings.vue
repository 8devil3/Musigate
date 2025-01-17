<template>
    <ContentLayout
        @submitted="submit()"
        title="Impostazioni prenotazioni"
        icon="fa-solid fa-gears"
    >
        <template #content>
            <!-- vista calendario -->
            <FormElement>
                <template #title>
                    Vista calendario
                </template>

                <template #description>
                    Scegli la vista predefinita per il calendario delle prenotazioni.
                </template>

                <template #content>
                    <Select v-model="form.default_calendar_view" :options="calendarViews" class="max-w-xs" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- preavviso -->
            <FormElement>
                <template #title>
                    Preavviso
                </template>

                <template #description>
                    Inserisci il periodo minimo di preavviso per prenotare, espresso in giorni.<br>
                    Esempio: 1 giorno indica che un artista deve prenotare minimo un giorno prima della sessione.
                </template>

                <template #content>
                    <NumberInput v-model.number="form.booking_advance" :error="form.errors.booking_advance" :min="0" :max="form.max_booking_horizon -1" unit="giorni" required />
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
                    <NumberInput v-model.number="form.max_booking_horizon" :error="form.errors.max_booking_horizon" :min="7" :max="365" unit="giorni"required />
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
                    Per abilitare la sincronizzazione devi affettuare l'accesso con Google e autorizzare l'utilizzo dei calendari.
                </template>

                <template #content>
                    <div class="flex flex-col gap-4">
                        <div class="space-y-4">
                            <Toggle v-model="form.has_sync" :disabled="!props.has_google_calendar_scope" :label="form.has_sync ? 'Sincronizzazione abilitata' : 'Sincronizzazione disabilitata'" />

                            <InfoBlock v-if="props.has_google_id && !props.has_google_calendar_scope" title="Sincronizzazione non disponibile" color="warning">
                                Hai effettuato l'acesso con Google ma non hai autorizzato l'utilizzo dei calendari per abilitare la sincronizzazione. Effettua il log out e accedi con Google autorizzando l'utilizzo dei calendari.
                            </InfoBlock>

                            <InfoBlock v-else-if="!props.has_google_id && !props.has_google_calendar_scope" title="Sincronizzazione non disponibile" color="danger">
                                Devi accedere con Google e autorizzare l'utilizzo dei calendari per abilitare la sincronizzazione. Effettua il log out e accedi con Google autorizzando l'utilizzo dei calendari.
                            </InfoBlock>
                        </div>

                        <template v-if="form.has_sync && props.has_google_calendar_scope && props.google_calendar_ids.length">
                            <Select v-model="form.sync_mode" isArray :options="['unidirezionale', 'bidirezionale']" default="Seleziona modalità" class="w-full max-w-xs" />
                            <Select v-model="form.google_calendar_id" isArray :options="props.google_calendar_ids" default="Seleziona un calendario" class="w-full max-w-xs" />
                        </template>
                    </div>
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
        </template>

        <template v-if="form.isDirty && !form.processing" #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import InfoBlock from '@/Components/InfoBlock.vue';

const props = defineProps({
    booking_settings: Object,
    google_calendar_ids: Array,
    has_google_id: Boolean,
    has_google_calendar_scope: Boolean,
});

const form = useForm({
    booking_advance: props.booking_settings.booking_advance,
    max_booking_horizon: props.booking_settings.max_booking_horizon,
    allow_fractional_durations: props.booking_settings.allow_fractional_durations,
    has_sync: props.booking_settings.has_sync,
    sync_mode: props.booking_settings.sync_mode ?? '',
    google_calendar_id: props.booking_settings.google_calendar_id ?? '',
    default_calendar_view: props.booking_settings.default_calendar_view ?? 'dayGridMonth',
});

const submit = () => {
    if(form.processing) return;
    form.put(route('bookings.settings.update'));
};

const calendarViews = {
    dayGridMonth: 'Mensile',
    timeGridWeek: 'Settimanale',
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Impostazioni prenotazioni',
    }, {default: () => page}),
};
</script>
