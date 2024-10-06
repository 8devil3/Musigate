<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :title="props.room?.name ?? form.name"
        :onFail="form.hasErrors"
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
            <FormElement>
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
            </FormElement>
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

            <!-- prenotabile? -->
            <FormElement>
                <template #title>
                    Sala prenotabile
                </template>

                <template #description>
                    Scegli se rendere la sala prenotabile.<br>
                    Se non prenotabile puoi inserire una tariffa che verrà mostrata al pubblico ma sarà solo indicativa.<br>
                    Se prenotabile è obbligatoria almeno una tariffa.
                </template>

                <template #content>
                    <Toggle v-model="form.is_bookable" :label="form.is_bookable ? 'Sala prenotabile' : 'Sala non prenotabile'" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- tariffe -->
            <FormElement>
                <template #title>
                    Tariffe
                </template>

                <template #description>
                    Inserisci la tariffa espressa in €/h.<br>
                    Puoi inserire anche una tariffa scontata che prevale su quella intera nelle prenotazioni.
                </template>

                <template #content>
                    <div class="space-y-6">
                        <NumberInput v-model.number="form.price" label="Tariffa intera" :min="2" :max="999" unit="€/h" :error="form.errors.price" :required="form.is_bookable" />

                        <div>
                            <Toggle v-model="form.has_discounted_price" :label="form.has_discounted_price ? 'Tariffa scontata abilitata' : 'Tariffa scontata disabilitata'" />
                        </div>
    
                        <NumberInput v-if="form.has_discounted_price" v-model.number="form.discounted_price" label="Tariffa scontata" :min="1" :max="form.price -1" unit="€/h" :error="form.errors.discounted_price" :required="form.has_discounted_price" />
                    </div>
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
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Input from '@/Components/Form/Input.vue'
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
    price: props.room?.price ?? null,
    has_discounted_price: props.room?.has_discounted_price ?? false,
    discounted_price: props.room?.discounted_price ?? null,
    area: props.room?.area ?? null,
    max_capacity: props.room?.max_capacity ?? null,
    description: props.room?.description ?? null,
});

const submit = ()=>{
    if(form.processing) return;

    if(props.room.id){
        form.put(route('sale-prova.update', props.room.id), {
            preserveScroll: true
        });
    } else {
        form.post(route('sale-prova.store'));
    }
}

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                name: 'Descrizione',
                href: route('sale-prova.edit', props.room.id),
                active: route().current('rooms.edit', props.room.id)
            },
            {
                name: 'Equipaggiamento',
                href: route('sale-prova.equipment.edit', props.room.id),
                active: route().current('sale-prova.equipment.edit', props.room.id)
            },
            {
                name: 'Foto',
                href: route('sale-prova.photos.edit', props.room.id),
                active: route().current('sale-prova.photos.edit', props.room.id)
            },
        ];
    } else {
        return null;
    }
});
</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import { computed } from 'vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Descrizione',
    }, {default: () => page}),
};
</script>
