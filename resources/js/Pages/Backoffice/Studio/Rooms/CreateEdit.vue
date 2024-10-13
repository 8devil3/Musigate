<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :title="props.room?.name ?? form.name"
        icon="fa-solid fa-microphone-lines"
        :tabLinks="tabLinks"
        :backRoute="route('sale-prova.index')"
        showBackRoute
    >
        <template #content>
            <!-- nome -->
            <FormElement>
                <template #title>
                    Nome
                </template>

                <template #description>
                    Inserisci il nome della Sala.
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
                    Seleziona un colore di riferimento della Sala visualizzato nel calendario delle prenotazioni.
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
                    Scegli se rendere la sala visibile e ricercabile.
                </template>

                <template #content>
                    <Toggle v-model="form.is_visible" :label="form.is_visible ? 'Sala visibile e ricercabile' : 'Sala non visibile e non ricercabile'" />
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
                        <Toggle v-model="form.is_bookable" :label="form.is_bookable ? 'Sala prenotabile' : 'Sala non prenotabile'" :disabled="props.room.price_type === 'no_price'" />
    
                        <InfoBlock v-if="props.room.price_type === 'no_price'" icon="fa-solid fa-exclamation" color="warning" title="Non disponiile">
                            Non è possibile rendere prenotabile la Sala perché non ha alcuna tariffa impostata.
                        </InfoBlock>
                    </div>
                </template>
            </FormElement> -->
            <!-- / -->

            <!-- prenotazione minima -->
            <FormElement>
                <template #title>
                    Prenotazione minima
                </template>

                <template #description>
                    imposta la durata minima di una prenotazione espressa in ore.
                </template>

                <template #content>
                    <NumberInput
                        v-model="form.min_booking"
                        :min="1"
                        :max="24"
                        :error="form.errors.min_booking"
                    />
                </template>
            </FormElement>
            <!-- / -->

            <!-- area -->
            <FormElement>
                <template #title>
                    Area
                </template>

                <template #description>
                    Inserisci l'area della Sala in metri quadri.
                </template>

                <template #content>
                    <NumberInput v-model.number="form.area" :min="1" :max="999" unit="mq" :error="form.errors.area" required />
                </template>
            </FormElement>
            <!-- / -->

            <!-- capienza -->
            <FormElement>
                <template #title>
                    Capienza massima
                </template>

                <template #description>
                    Inserisci la capienza massima della Sala espressa in numero di persone.
                </template>

                <template #content>
                    <NumberInput v-model.number="form.max_capacity" :min="1" :max="99" unit="persone" :error="form.errors.max_capacity" required />
                </template>
            </FormElement>
            <!-- / -->

            <!-- presentazione -->
            <FormElement>
                <template #title>
                    Presentazione
                </template>

                <template #description>
                    Inserisci la presentazione della Sala di almeno 100 caratteri, spazi esclusi.
                </template>

                <template #content>
                    <Textarea v-model="form.description" title="Presentazione" placeholder="Presentazione della Sala" :minlength="100" :error="form.errors.desc" required class="w-full max-w-md"/>
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template #actions>
            <SaveButton v-if="form.isDirty && !form.processing" />
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
    room: Object,
});

const form = useForm({
    name: props.room?.name ?? 'Nuova sala',
    color: props.room?.color ?? '#ff6600',
    is_visible: props.room?.is_visible ?? false,
    is_bookable: props.room?.is_bookable ?? false,
    min_booking: props.room?.min_booking ?? 1,
    area: props.room?.area ?? null,
    max_capacity: props.room?.max_capacity ?? null,
    description: props.room?.description ?? null,
});

const submit = ()=>{
    if(form.processing) return;

    if(props.room.id){
        form.put(route('sale-prova.update', props.room.id), {
            preserveState: false
        });
    } else {
        form.post(route('sale-prova.store'), {
            preserveState: false
        });
    }
}

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                label: 'Descrizione',
                route: 'sale-prova.edit',
                params: props.room.id,
            },
            {
                label: 'Tariffe',
                route: 'sale-prova.prices.edit',
                params: props.room.id,
            },
            {
                label: 'Equipaggiamento',
                route: 'sale-prova.equipment.edit',
                params: props.room.id,
            },
            {
                label: 'Foto',
                route: 'sale-prova.photos.edit',
                params: props.room.id,
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
