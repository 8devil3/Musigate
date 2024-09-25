<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :title="props.room.name"
        :onFail="form.hasErrors"
        icon="fa-solid fa-microphone-lines"
        :tabLinks="tabLinks"
        :backRoute="route('rooms.index')"
    >
        <template #content>
            <!-- status -->
            <FormElement>
                <template #title>
                    Status
                </template>

                <template #description>
                    Lo status attuale della Sala.
                </template>

                <template #content>
                    <RoomStatus :statusId="props.room.room_status_id" :statusName="props.room_statuses[props.room.room_status_id]" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- nome -->
            <FormElement>
                <template #title>
                    Nome
                </template>

                <template #description>
                    Inserisci il nome della Sala.
                </template>

                <template #content>
                    <Input id="room-name" v-model="form.name" title="Nome della Sala" :error="form.errors.name" class="w-full max-w-xs" required />
                </template>
            </FormElement>
            <!-- / -->

            <!-- colore -->
            <FormElement>
                <template #title>
                    Colore
                </template>

                <template #description>
                    Seleziona il colore della Sala: è il colore di riferimento della Sala nei calendari Musigate.
                </template>

                <template #content>
                    <div class="flex items-center justify-start gap-2">
                        <ColorPicker id="room-color" title="Colore" v-model="form.color" :error="form.errors.color"/>
                        {{ form.color }}
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- tipo -->
            <FormElement>
                <template #title>
                    Tipo
                </template>

                <template #description>
                    Seleziona il tipo di Sala.
                </template>

                <template #content>
                    <Select v-model.number="form.room_type_id" id="room-type" default="Seleziona tipo" title="Tipo di Sala" :options="props.room_types" :error="form.errors.room_type_id" class="w-full max-w-xs" required />
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
                    <div class="flex items-center gap-2">
                        <NumberInput v-model.number="form.area" id="room-area" :min="1" :max="999" required />
                        mq
                    </div>
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
                    <div class="flex items-center gap-2">
                        <NumberInput v-model.number="form.max_capacity" id="room-max-capacity" :min="1" :max="99" required />
                        persone
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- tariffa minima -->
            <FormElement>
                <template #title>
                    Tariffa minima
                </template>

                <template #description>
                    Inserisci la tariffa minima, espressa in €/h.<br>
                    Verrà mostrata al pubblico come la tariffa "a partire da..." ed ha una funzione indicativa e non vincolante.<br>
                    Inserendo 0 (zero), la tariffa non verrà mostrata.
                </template>

                <template #content>
                    <div class="flex items-center gap-2">
                        <NumberInput v-model.number="form.min_price" id="room-min-price" :min="0" :max="999" required />
                        €/h
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
                    <Textarea v-model="form.description" title="Presentazione" placeholder="Presentazione della Sala" :minlength="100" :error="form.errors.desc" class="w-full max-w-md"/>
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
import Select from '@/Components/Form/Select.vue';
import ColorPicker from '@/Components/Form/ColorPicker.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import RoomStatus from '@/Components/Backoffice/RoomStatus.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';

const props = defineProps({
    room: Object,
    room_types: Object,
    room_statuses: Object,
});

const form = useForm({
    name: props.room.name,
    room_type_id: props.room.room_type_id,
    color: props.room.color,
    min_price: props.room.min_price,
    description: props.room.desc,
    area: props.room.area,
    max_capacity: props.room.max_capacity,
});

const submit = ()=>{
    form.put(route('rooms.description.update', props.room.id), {
        preserveScroll: true
    });
}

const tabLinks = [
    {
        name: 'Descrizione',
        href: route('rooms.description.edit', props.room.id),
        active: route().current('rooms.description.edit', props.room.id)
    },
    {
        name: 'Equipaggiamento',
        href: route('rooms.equipment.edit', props.room.id),
        active: route().current('rooms.equipment.edit', props.room.id)
    },
    {
        name: 'Foto',
        href: route('rooms.photos.edit', props.room.id),
        active: route().current('rooms.photos.edit', props.room.id)
    },
];
</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Descrizione',
    }, {default: () => page}),
};
</script>
