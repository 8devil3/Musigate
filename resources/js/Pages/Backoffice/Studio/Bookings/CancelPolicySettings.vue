<template>
    <ContentLayout
        @submitted="submit()"
        title="Policy annullamenti"
        icon="fa-solid fa-calendar-xmark"
    >
        <template #content>
            <!-- buffer -->
            <FormElement>
                <template #title>
                    Abilita/disabilita policy
                </template>

                <template #description>
                    Abilita/disabilita la gestione degli annullamenti delle prenotazioni.<br>
                    Se <strong>abilitata</strong>, potrai impostare le modalità di rimborso automatico in caso di annullamento.<br>
                    Se <strong>disabilitata</strong>, l'artista non verrà mai rimborsato in caso di annullamento.
                </template>

                <template #content>
                    <Toggle v-model="form.has_cancel_policy" :label="form.has_cancel_policy ? 'Policy annullamenti abilitata' : 'Policy annullamenti disabilitata'" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- nessun rimborso -->
            <FormElement v-if="form.has_cancel_policy">
                <template #title>
                    Nessun rimborso
                </template>

                <template #description>
                    Seleziona la quantità di ore, precedenti alla sessione, che non prevedono un rimborso.
                </template>

                <template #content>
                    <div class="space-y-4">
                        <RangeSlider
                            v-model.number="form.no_refund_hours"
                            :label="form.no_refund_hours + ' ore'"
                            @change="setPartialRefund()"
                            :disabled="!form.has_cancel_policy"
                            :step="4"
                            :min="4"
                            :max="48"
                        />
                        <div>
                            Nessun rimborso fino a <span class="font-semibold text-orange-500">{{ form.no_refund_hours }}ore </span> prima della data di prenotazione.
                        </div>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- rimborso parziale -->
            <FormElement v-if="form.has_cancel_policy">
                <template #title>
                    Rimborso parziale
                </template>

                <template #description>
                    Seleziona la quantità di ore, precedenti alla sessione, che determinano un rimborso parziale e la percentuale di rimborso.
                </template>

                <template #content>
                    <div class="space-y-8">
                        <RangeSlider
                            v-model.number="form.partial_refund_hours"
                            :label="form.partial_refund_hours + ' ore'"
                            :disabled="!form.has_cancel_policy"
                            :step="4"
                            :min="parseInt(form.no_refund_hours) + 8"
                            :max="96"
                        />

                        <RangeSlider
                            v-model.number="form.partial_refund_percentage"
                            :label="form.partial_refund_percentage + '%'"
                            :disabled="!form.has_cancel_policy"
                            :step="5"
                            :min="10"
                            :max="90"
                        />

                        <div>
                            Da <span class="font-semibold text-orange-500">{{ form.no_refund_hours }} ore</span> e fino a <span class="font-semibold text-orange-500">{{ form.partial_refund_hours }} ore</span> prima della data di prenotazione, l'artista riceverà un rimborso del <span class="font-semibold text-orange-500">{{ form.partial_refund_percentage }}%</span>.<br>
                            Oltre <span class="font-semibold text-orange-500">{{ form.partial_refund_hours }} ore</span> riceverà il rimborso totale (100%).
                        </div>
                    </div>
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
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import RangeSlider from '@/Components/Form/RangeSlider.vue';

const props = defineProps({
    cancel_settings: Object,
})

const form = useForm({
    has_cancel_policy: props.cancel_settings?.has_cancel_policy,
    no_refund_hours: props.cancel_settings?.no_refund_hours ?? 24,
    partial_refund_hours: props.cancel_settings?.partial_refund_hours ?? 48,
    partial_refund_percentage: props.cancel_settings?.partial_refund_percentage ?? 50,
});

const setPartialRefund = ()=>{
    if(form.partial_refund_hours <= form.no_refund_hours){
        form.partial_refund_hours = form.no_refund_hours +8;
    }
}

const submit = () => {
    if(form.processing) return;
    form.put(route('cancelling.settings.update'));
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Policy annullamenti',
    }, {default: () => page}),
};
</script>
