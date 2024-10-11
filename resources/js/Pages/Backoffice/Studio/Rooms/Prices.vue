<template>
    <ContentLayout
        @submitted="submit()"
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
                    Tipo di tariffazione
                </template>

                <template #description>
                    Imposta il tipo di tariffa della Sala.
                </template>

                <template #content>
                    <div class="space-y-2">
                        <Radio v-for="type, key in props.price_types" @change="form.price_type === 'timebands_price' ? setTimebandPrices() : null" v-model="form.price_type" :value="key" name="price-type">
                            {{ type }}
                        </Radio>
                    </div>
                </template>
            </FormElement>

            <template v-if="form.price_type !== 'no_price'">
                <!-- tariffa fissa -->
                <FormElement v-if="form.price_type === 'fixed_price'">
                    <template #title>
                        Tariffa fissa
                    </template>

                    <template #description>
                        Inserisci il valore della tariffa fissa ed eventualmente anche un valore scontato.<br>
                        La tariffa fissa rimane costante per l'intero ciclo settimanale.
                    </template>

                    <template #content>
                        <div class="space-y-4">
                            <NumberInput v-model="form.fixed_price" label="Tariffa base" unit="€/h" required />
                            <Toggle
                                v-model="form.has_discounted_fixed_price"
                                label="Abilita sconto"
                            />
                            <NumberInput v-if="form.has_discounted_fixed_price" label="Tariffa scontata" v-model="form.discounted_fixed_price" unit="€/h" required />
                        </div>
                    </template>
                </FormElement>
                <!-- / -->

                <!-- tariffa con fasce orarie -->
                <template v-else-if="form.price_type === 'timebands_price' && props.timebands.length">
                    <FormElement v-for="wd, wdKey in weekdays">
                        <template #title>
                            {{ wd }}
                        </template>

                        <template #description>
                            Inserisci le tariffe della giornata di {{ wd }}.
                        </template>

                        <template #content>
                            <div v-if="props.timebands.filter(tb => tb.weekday == wdKey).length" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <template v-for="timeband in props.timebands.filter(tb => tb.weekday == wdKey)" >
                                    <div v-for="tbPrice in form.timeband_prices.filter(tbp => tbp.timeband_id === timeband.id)" class="flex flex-col gap-6 p-4 border rounded-xl border-slate-400">
                                        <div class="space-y-0.5">
                                            <div class="text-xs font-normal uppercase text-slate-400">Fascia oraria</div>
                                            <h4 class="p-0 m-0 text-base">
                                                {{ wd.slice(0, 3) }} - {{ timeband.name }}
                                            </h4>
                                            <div class="text-xs font-normal text-slate-300">
                                                {{ timeband.start }} - {{ timeband.end }}
                                            </div>
                                        </div>
                                        
                                        <NumberInput v-model="tbPrice.price" label="Tariffa base" :min="1" unit="€/h" required />
            
                                        <Toggle v-model="tbPrice.has_discounted_price" label="Abilita sconto" />
            
                                        <NumberInput v-if="tbPrice.has_discounted_price" v-model="tbPrice.discounted_price" label="Tariffa scontata" :min="1" unit="€/h" required />
                                    </div>
                                </template>
                            </div>

                            <Empty v-else icon="fa-solid fa-clock">
                                <template #title>
                                    Nessuna fascia oraria di {{ wd }}
                                </template>
                                <template #description>
                                    Non hai impostato le fasce orarie di {{ wd }}. Per inserire le tariffe con fasce orarie è necessario prima impostarle.
                                </template>
                                <template #actions>
                                    <Button type="router" :href="route('studio.availability.edit')" text="Imposta fasce orarie" icon="fa-solid fa-clock" />
                                </template>
                            </Empty>
                        </template>
                    </FormElement>
                </template>
                <!-- / -->
            </template>
        </template>

        <template #actions>
            <SaveButton :disabled="form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Radio from '@/Components/Form/Radio.vue';
import Select from '@/Components/Form/Select.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import Input from '@/Components/Form/Input.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';

const props = defineProps({
    room: Object,
    open_weekdays: Array,
    weekday: Number,
    timebands: Object,
    timeband_prices: Object,
    price_types: Object,
});

const form = useForm({
    weekday: props.weekday ?? 1,
    price_type: props.room.price_type,
    fixed_price: props.room.fixed_price,
    has_discounted_fixed_price: props.room.has_discounted_fixed_price,
    discounted_fixed_price: props.room.discounted_fixed_price,
    timeband_prices: props.timeband_prices ?? [],
});

const setTimebandPrices = ()=>{
    if(form.price_type === 'timebands_price' && !props.timeband_prices.length){
        form.timeband_prices = [];
        props.timebands.forEach(tb => {
            form.timeband_prices.push({
                room_id: tb.room_id,
                timeband_id: tb.id,
                price: null,
                has_discounted_price: false,
                discounted_price: null,
            });
        });
    } else if (form.price_type !== 'timebands_price'){
        form.timeband_prices = [];
    }
};

const submit = ()=>{
    // form.put(route('sale-prova.prices.update', props.room.id));
};

const weekdays = computed(()=>{
    let wds = {};

    props.open_weekdays.forEach(wd => {
        wds[wd] = weekdaysList[wd];
    });

    return wds;
});

const weekdaysList = {
    1: 'Lunedì',
    2: 'Martedì',
    3: 'Mercoledì',
    4: 'Giovedì',
    5: 'Venerdì',
    6: 'Sabato',
    7: 'Domenica',
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
