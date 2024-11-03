<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.bundle.name"
        icon="fa-solid fa-euro"
        backRoute="pacchetti.index"
        :tabLinks="tabLinks"
    >
        <template #content>
            <!-- tipo di tariffa -->
            <FormElement>
                <template #title>
                    Tipo di tariffazione
                </template>

                <template #description>
                    Imposta il tipo di tariffa del pacchetto.
                </template>

                <template #content>
                    <div class="space-y-2">
                        <Radio v-for="type, key in props.price_types" v-model="form.price_type" :value="key" name="bundle-price-type" :disabled="key === 'timebands_price' && !props.timebands.length">
                            {{ type }}
                        </Radio>

                        <p v-if="!props.timebands.length" class="text-xs">
                            Per abilitare le tariffe a fasce orarie occorre prima impostare le <Link :href="route('studio.availability.index')" class="font-normal text-orange-500 underline transition-colors hover:text-orange-400">fasce orarie</Link>.
                        </p>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- tariffa fissa -->
            <FormElement v-if="form.price_type === 'fixed_price'">
                <template #title>
                    Tariffa fissa
                </template>

                <template #description>
                    Inserisci il valore della tariffa fissa ed eventualmente anche un valore scontato.
                </template>

                <template #content>
                    <div class="space-y-4">
                        <NumberInput v-model="form.fixed_price" label="Tariffa base" :min="2" unit="€" :error="form.errors.fixed_price" required />
                        <Toggle
                            v-model="form.has_discounted_fixed_price"
                            label="Abilita sconto"
                        />
                        <NumberInput v-if="form.has_discounted_fixed_price" label="Tariffa scontata" v-model="form.discounted_fixed_price" :min="1" :max="form.fixed_price -1" unit="€" required />
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- tariffa a giornata -->
            <template v-else-if="form.price_type === 'weekdays_price'">
                <FormElement v-for="owd in props.open_weekdays">
                    <template #title>
                        <span class="capitalize">
                            {{ owd.label }}
                        </span>
                    </template>

                    <template #description>
                        <template v-if="owd.weekday <= 7">
                            Inserisci la tariffa della giornata di
                        </template>
                        <template v-else>
                            Inserisci la tariffa dei
                        </template>
                        {{ owd.label }}.
                    </template>

                    <template #content>
                        <div class="space-y-4">
                            <NumberInput v-model="form.weekday_prices.find(wdp => wdp.availability_id == owd.availability_id).price" label="Tariffa base" :min="1" unit="€" required />

                            <Toggle v-model="form.weekday_prices.find(wdp => wdp.availability_id == owd.availability_id).has_discounted_price" label="Abilita sconto" />

                            <NumberInput v-if="form.weekday_prices.find(wdp => wdp.availability_id == owd.availability_id).has_discounted_price" v-model="form.weekday_prices.find(wdp => wdp.availability_id == owd.availability_id).discounted_price" label="Tariffa scontata" :min="1" unit="€" required />
                        </div>
                    </template>
                </FormElement>
            </template>
            <!-- / -->

            <!-- tariffa con fasce orarie -->
            <template v-else-if="form.price_type === 'timebands_price' && props.timebands.length">
                <FormElement v-for="owd in props.open_weekdays">
                    <template #title>
                        <span class="capitalize">{{ owd.label }}</span>
                    </template>

                    <template #description>
                        Inserisci le tariffe della giornata di {{ owd.label }}.
                    </template>

                    <template #content>
                        <div v-if="props.timebands.filter(tb => tb.availability_id == owd.availability_id).length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                            <template v-for="timeband in props.timebands.filter(tb => tb.availability_id == owd.availability_id)" >
                                <div v-for="tbPrice, index in form.timeband_prices.filter(tbp => tbp.timeband_id == timeband.id)" class="flex flex-col gap-6 p-4 border rounded-2xl border-slate-400">
                                    <div class="space-y-1">
                                        <div class="text-xs font-normal leading-none uppercase text-slate-400">Fascia oraria</div>
                                        <h4 class="w-full p-0 m-0 text-base truncate">
                                            {{ timeband.name }}
                                        </h4>
                                        <div class="text-xs font-normal text-slate-300">
                                            {{ timeband.start }} - {{ timeband.end }}
                                        </div>
                                    </div>

                                    <NumberInput v-model="tbPrice.price" label="Tariffa base" :min="1" unit="€" required />

                                    <Toggle v-model="tbPrice.has_discounted_price" label="Abilita sconto" />

                                    <NumberInput v-if="tbPrice.has_discounted_price" v-model="tbPrice.discounted_price" label="Tariffa scontata" :min="1" unit="€" required />
                                </div>
                            </template>
                        </div>

                        <Empty v-else icon="fa-solid fa-clock">
                            <template #title>
                                Nessuna fascia oraria di {{ owd.label }}
                            </template>
                            <template #description>
                                Non hai impostato le fasce orarie di {{ owd.label }}. Per inserire le tariffe con fasce orarie è necessario prima impostarle.
                            </template>
                            <template #actions>
                                <Button type="router" :href="route('studio.availability.edit', owd.availability_id)" text="Imposta fasce orarie" icon="fa-solid fa-clock" />
                            </template>
                        </Empty>
                    </template>
                </FormElement>
            </template>
            <!-- / -->
        </template>

        <template v-if="form.isDirty && !form.processing" #actions>
            <Button @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Radio from '@/Components/Form/Radio.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';

const props = defineProps({
    bundle: Object,
    open_weekdays: Object,
    timebands: Object,
    timeband_prices: Object,
    weekday_prices: Object,
    price_types: Object,
});

const form = useForm({
    price_type: props.bundle.price_type,
    fixed_price: props.bundle.fixed_price,
    has_discounted_fixed_price: props.bundle.has_discounted_fixed_price,
    discounted_fixed_price: props.bundle.discounted_fixed_price,
    timeband_prices: props.timeband_prices ?? [],
    weekday_prices: props.weekday_prices ?? [],
});

const submit = ()=>{
    form.put(route('pacchetti.prices.update', props.bundle.id), {
        preserveState: false
    });
};

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
            {
                label: 'Foto',
                route: 'pacchetti.photo.edit',
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
        title: 'Tariffe',
    }, {default: () => page}),
};
</script>
