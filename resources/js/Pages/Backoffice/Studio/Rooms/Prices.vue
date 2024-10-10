<template>
    <ContentLayout
        as="div"
        :isLoading="form.processing"
        :title="props.room.name"
        icon="fa-solid fa-euro"
        :backRoute="route('rooms.index')"
        showBackRoute
        :tabLinks="tabLinks"
    >
        <template #content>
            <FormElement>
                <template #title>
                    Lunedì
                </template>

                <template #description>
                    Inserisci le fasce orarie e le tariffe del lunedì.
                </template>

                <template #content>
                    <form @submit.prevent="submitModay()" v-if="form[1].length" class="w-full pb-4 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="px-1 py-2 text-center uppercase bg-slate-800 text-slate-400">fascia oraria</th>
                                    <th class="px-1 py-2 text-center uppercase bg-slate-800 text-slate-400">dalle ore</th>
                                    <th class="px-1 py-2 text-center uppercase bg-slate-800 text-slate-400">alle ore</th>
                                    <th class="px-1 py-2 text-center uppercase bg-slate-800 text-slate-400">tariffa</th>
                                    <th class="px-1 py-2 text-center uppercase bg-slate-800 text-slate-400">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="price, index in form[1]">
                                    <td class="w-full px-1 py-2 text-center min-w-32">
                                        <Input v-model="price.name" placeholder="Nome fascia oraria" autofocus required />
                                    </td>
                                    <td class="px-1 py-2 text-center">
                                        <!-- <VueDatePicker
                                            v-model="price.start"
                                            time-picker
                                            :start-time="{hours: dayjs().format('HH'), minutes: 0}"
                                            minutes-increment="30"
                                            minutes-grid-increment="30"
                                            required
                                            dark
                                        /> -->

                                        <Input
                                            type="time"
                                            v-model="price.start"
                                            :step="props.allow_fractional_durations ? 1800 : 3600"
                                            :disabled="index > 0"
                                            required
                                        />
                                    </td>
                                    <td class="px-1 py-2 text-center">
                                        <!-- <VueDatePicker
                                            v-model="price.end"
                                            time-picker
                                            :min-time="price.start ? { hours: price.start.hours +1, minutes: 0 } : null"
                                            :start-time="{hours: dayjs().add(1, 'hour').format('HH'), minutes: 0}"
                                            minutes-increment="30"
                                            minutes-grid-increment="30"
                                            required
                                            dark
                                            :disabled="!price.start"
                                        /> -->

                                        <Input
                                            type="time"
                                            v-model="price.end"
                                            @change="setTimeStartEnd(1, index)"
                                            :step="props.allow_fractional_durations ? 1800 : 3600"
                                            :disabled="!price.start"
                                            required
                                        />
                                    </td>
                                    <td class="px-1 py-2 text-center">
                                        <NumberInput v-model="price.price" :min="1" unit="€/h" required class="mx-auto" />
                                    </td>
                                    <td class="w-10 px-1 py-2 text-center">
                                        <ActionButton @click="deletePrice(1, index)" icon="fa-solid fa-trash-can" color="red" title="Elimina" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-2">
                            <button type="button" @click="addPrice(1)" class="p-1 text-sm font-semibold text-orange-500 transition-colors hover:text-orange-400">
                                <i class="fa-solid fa-plus" />
                                Aggiungi
                            </button>
                        </div>

                        <div class="mt-6 text-right">
                            <SaveButton :isLoading="form.processing" :disabled="form.processing" />
                        </div>
                    </form>

                    <Empty v-else icon="fa-solid fa-euro">
                        <template #title>
                            Nessuna tariffa
                        </template>
                        <template #description>
                            Non sono presenti tariffe per il lunedì.
                        </template>
                        <template #actions>
                            <Button @click="addPrice(1)" text="Aggiungi" icon="fa-solid fa-plus" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <!-- <template #actions>
            <SaveButton :isLoading="isLoading" :disabled="isLoading" />
        </template> -->
    </ContentLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Input from '@/Components/Form/Input.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import dayjs from 'dayjs';
// import VueDatePicker from '@vuepic/vue-datepicker';
// import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    room: Object,
    prices: Object,
    allow_fractional_durations: Boolean,
});

const form = useForm({
    1: [],
    2: [],
    3: [],
    4: [],
    5: [],
    6: [],
    7: [],
});

const submitModay = ()=>{
    // form.put(route('rooms.prices.update', props.room.id));
};

const setTimeStartEnd = (weekday, index)=>{
    if(form[weekday].length > 1){
        let start = '';
        let end = '';

        form[weekday].forEach((item, i) => {
            if(i > index){
                i = i <= form[weekday].length -2 ? i +1 : i;
                start = dayjs(dayjs().format('YYYY-MM-DD') + ' ' +  form[weekday][i].end);
                end = start.clone().add(1, 'hour');

                item.start = start.format('HH:mm');
                item.end = end.format('HH:mm');
            }
        });
    }
};

const addPrice = (weekday)=>{
    let index = form[1].length;

    if(form[weekday].length){
        let start = dayjs(dayjs().format('YYYY-MM-DD') + ' ' +  form[weekday][index -1].end);
        let end = start.clone().add(1, 'hour');

        form[weekday].push({
            name: null,
            start: start.format('HH:mm'),
            end: end.format('HH:mm'),
            price: 20,
        });
    } else {
        form[weekday].push({
            name: null,
            start: dayjs().minute(0).second(0).format('HH:mm'),
            end: dayjs().add(1, 'hour').minute(0).second(0).format('HH:mm'),
            price: 20,
        });
    }
};

const deletePrice = (weekday, index)=>{
    form[weekday].splice(index, 1);
};

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                name: 'Descrizione',
                href: route('sale-prova.edit', props.room.id),
                active: route().current('sale-prova.edit', props.room.id)
            },
            {
                name: 'Tariffe',
                href: route('sale-prova.prices.edit', props.room.id),
                active: route().current('sale-prova.prices.edit', props.room.id)
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

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Tariffe',
    }, {default: () => page}),
};
</script>
