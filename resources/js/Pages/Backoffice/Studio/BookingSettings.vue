<template>
    <BackofficeLayout
        @submitted="submit()"
        title="Impostazioni prenotazioni"
        icon="fa-solid fa-gears"
        :backRoute="route('studio.links')"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
    >
        <template #content>
            <!-- prenotazione minima -->
            <FormElement>
                <template #title>
                    Prenotazione minima
                </template>

                <template #description>
                    Inserisci il tempo minimo di prenotazione, espresso in ore.
                </template>

                <template #content>
                    <div class="flex items-center gap-3">
                        <NumberInput v-model.number="form.min_booking" :error="form.errors.min_booking" :min="1" :max="8" required class="w-32" />
                        {{ form.min_booking === 1 ? 'ora' : 'ore' }}
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- Anticipo -->
            <FormElement>
                <template #title>
                    Anticipo
                </template>

                <template #description>
                    Inserisci il periodo minimo di anticipo per prenotare, espresso in giorni.<br>
                    Esempio: 1 giorno indica che un artista deve prenotare minimo il giorno prima della sessione.
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
                    Inserisci il periodo massimo futuro di prenotazione, espresso in giorni. Rappresenta la finestra temporale nel futuro in cui un artista può prenotare.<br>
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
                    Abilita/disablita le pause tra le sessioni. Il valore della pausa è espresso in minuti.
                </template>

                <template #content>
                    <Toggle v-model="form.has_buffer" :label="form.has_buffer ? 'Pause abilitate' : 'Pause disabilitate'" />

                    <div v-if="form.has_buffer" class="flex items-center gap-3 mt-4">
                        <NumberInput v-model.number="form.buffer" :error="form.errors.buffer" :min="5" :max="60" required class="w-32" />
                        minuti
                    </div>
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
                    Per abilitare la sincronizzazione devi affettuare l'accesso con un account Google.
                </template>

                <template #content>
                    <div class="flex flex-col gap-4">
                        <div class="space-y-2">
                            <Toggle v-model="form.has_sync" :disabled="!props.google_token" :label="form.has_sync ? 'Sincronizzazione abilitata' : 'Sincronizzazione disabilitata'" />

                            <div v-if="!props.google_token" class="text-sm text-red-500">
                                Devi accedere con un account Google per abilitare la sincronizzazione.<br>
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
    buffer: props.booking_settings.buffer ?? 30,
    has_sync: props.booking_settings.has_sync,
    sync_mode: props.booking_settings.sync_mode ?? '',
    google_calendar_id: props.booking_settings.google_calendar_id ?? '',
});

const submit = () => {
    if(form.processing) return;
    form.put(route('bookings.settings.update'));
};

</script>
