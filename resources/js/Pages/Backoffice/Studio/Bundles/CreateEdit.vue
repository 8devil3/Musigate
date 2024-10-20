<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.bundle?.name ?? form.name"
        icon="fa-solid fa-store"
        :tabLinks="tabLinks"
        backRoute="pacchetti.index"
    >
        <template #content>
            <!-- nome -->
            <FormElement>
                <template #title>
                    Nome
                </template>

                <template #description>
                    Inserisci il nome del paccehtto.
                </template>

                <template #content>
                    <Input v-model="form.name" :error="form.errors.name" required class="w-full max-w-xs" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- colore -->
            <!-- <FormElement>
                <template #title>
                    Colore
                </template>

                <template #description>
                    Seleziona un colore di riferimento del paccehtto visualizzato nel calendario delle prenotazioni.
                </template>

                <template #content>
                    <div class="flex items-center justify-start gap-2">
                        <ColorPicker v-model="form.color" :error="form.errors.color"/>
                        {{ form.color }}
                    </div>
                </template>
            </FormElement> -->
            <!-- / -->

            <!-- visibile? -->
            <FormElement>
                <template #title>
                    Visibilità
                </template>

                <template #description>
                    Scegli se rendere il pacchetto visibile e ricercabile.
                </template>

                <template #content>
                    <Toggle v-model="form.is_visible" :label="form.is_visible ? 'Pacchetto visibile e ricercabile' : 'Pacchetto non visibile e non ricercabile'" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- prenotabile? -->
            <!-- <FormElement>
                <template #title>
                    Sala prenotabile
                </template>

                <template #description>
                    Scegli se rendere la sala prenotabile.<br>
                    Se non prenotabile puoi inserire una tariffa che verrà mostrata al pubblico ma sarà solo indicativa.<br>
                    Se prenotabile è obbligatoria almeno una tariffa.
                </template>

                <template #content>
                    <div class="space-y-4">
                        <Toggle v-model="form.is_bookable" :label="form.is_bookable ? 'Sala prenotabile' : 'Sala non prenotabile'" :disabled="props.bundle.price_type === 'no_price'" />
    
                        <InfoBlock v-if="props.bundle.price_type === 'no_price'" icon="fa-solid fa-exclamation" color="warning" title="Non disponiile">
                            Non è possibile rendere prenotabile la Sala perché non ha alcuna tariffa impostata.
                        </InfoBlock>
                    </div>
                </template>
            </FormElement> -->
            <!-- / -->


            <!-- Descrizione -->
            <FormElement optional>
                <template #title>
                    Descrizione
                </template>

                <template #description>
                    Inserisci la Descrizione del paccehtto di almeno 100 caratteri, spazi esclusi.
                </template>

                <template #content>
                    <Textarea v-model="form.description" title="Descrizione" placeholder="Descrizione del paccehtto" :minlength="100" :error="form.errors.description" />
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
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Input from '@/Components/Form/Input.vue'
import InfoBlock from '@/Components/InfoBlock.vue'
import ColorPicker from '@/Components/Form/ColorPicker.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';

const props = defineProps({
    bundle: Object,
});

const form = useForm({
    name: props.bundle?.name ?? 'Nuovo pacchetto',
    // color: props.bundle?.color ?? '#ff6600',
    // is_bookable: props.bundle?.is_bookable ?? false,
    is_visible: props.bundle?.is_visible ?? false,
    description: props.bundle?.description ?? null,
});

const submit = ()=>{
    if(form.processing) return;

    if(props.bundle.id){
        form.put(route('pacchetti.update', props.bundle.id));
    } else {
        form.post(route('pacchetti.store'));
    }
}

const tabLinks = computed(()=>{
    if(props.bundle.id){
        return [
            {
                label: 'Descrizione',
                route: 'pacchetti.edit',
                params: props.bundle.id,
            },
            {
                label: 'Tariffe',
                route: 'pacchetti.prices.edit',
                params: props.bundle.id,
            },
        ];
    } else {
        return null;
    }
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Descrizione',
    }, {default: () => page}),
};
</script>
