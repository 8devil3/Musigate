<template>
    <ContentLayout
        @submitted="submit()"
        title="Impostazioni prenotazioni"
        icon="fa-solid fa-gears"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <!-- vista calendario -->
            <FormElement>
                <template #title>
                    Conto PayPal
                </template>

                <template #description>
                    Collega il tuo conto PayPal per abilitare i pagamenti delle prenotazioni. Il conto deve essere esclusivamente di tipo <strong>PayPal Business</strong>.
                </template>

                <template #content>
                    <a v-if="!props.has_paypal" :href="route('paypal.redirect')" class="inline-flex items-center gap-1 px-16 h-8 py-2 rounded-full bg-[#ffc439]">
                        <img src="/img/logo/logo_paypal.svg" class="h-full" />
                    </a>

                    <template v-else>
                        <InfoBlock icon="fa-solid fa-check" title="Conto PayPal collegato" color="success" />
                        <div class="mt-4">
                            <Button @click="router.patch('paypal.unlink')" text="Scollega conto PayPal" icon="fa-solid fa-arrow-right-from-bracket" />
                        </div>
                    </template>
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
                    <NumberInput v-model.number="form.min_booking" :error="form.errors.min_booking" :min="1" :max="8" unit="ore" required />
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

                            <InfoBlock v-if="props.has_google_id && !props.has_google_calendar_scope" title="Sincronizzazione non disponibile" icon="fa-solid fa-circle-exclamation" color="warning">
                                Hai effettuato l'acesso con Google ma non hai autorizzato l'utilizzo dei calendari per abilitare la sincronizzazione. Effettua il log out e accedi con Google autorizzando l'utilizzo dei calendari.
                            </InfoBlock>

                            <InfoBlock v-else-if="!props.has_google_id && !props.has_google_calendar_scope" title="Sincronizzazione non disponibile" icon="fa-solid fa-circle-exclamation" color="danger">
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

            <!-- buffer -->
            <FormElement>
                <template #title>
                    Pause
                </template>

                <template #description>
                    Abilita/disabilita le pause di 30 minuti tra le sessioni.<br>
                    Puoi scegliere di applicare le pause anche agli eventi importati.<br>
                    Se abilitata, verrà attivata automaticamente anche l'impostazione <strong>Durate frazionate</strong>
                </template>

                <template #content>
                    <Toggle v-model="form.has_buffer" @click="forceAllowFractionlDurations()" :label="form.has_buffer ? 'Pause abilitate' : 'Pause disabilitate'" />
                    <div v-if="form.has_sync && form.has_buffer" class="mt-4">
                        <Toggle v-model="form.buffer_on_imported_event" :label="form.buffer_on_imported_event ? 'Pause eventi importati abilitate' : 'Pause eventi importati disabilitate'" />

                        <p class="mt-2 text-xs text-slate-400">Puoi decidere di calcolare 30 minuti di pausa anche agli eventi importati. Per esempio se un evento importato dura 2 ore, abilitando l'impostazione verrà calcolato come 2,5 ore.</p>
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

        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import Button from '@/Components/Form/Button.vue';
import InfoBlock from '@/Components/InfoBlock.vue';

const props = defineProps({
    booking_settings: Object,
    google_calendar_ids: Array,
    has_google_id: Boolean,
    has_google_calendar_scope: Boolean,
    has_paypal: Boolean,
});

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
    default_calendar_view: props.booking_settings.default_calendar_view ?? 'dayGridMonth',
    buffer_on_imported_event: props.booking_settings.buffer_on_imported_event,
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

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Impostazioni prenotazioni',
    }, {default: () => page}),
};
</script>
