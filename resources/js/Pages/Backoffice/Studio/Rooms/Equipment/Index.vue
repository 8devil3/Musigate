<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.room.name"
        icon="fa-solid fa-sliders"
        :tabLinks="tabLinks"
        backRoute="sale-prova.index"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Click o tap sulla categoria per gestirne l'equipaggiamento.
                </template>

                <template #content>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <Link v-for="cat, catId in props.categories" :href="route('sale-prova.equipment.edit', [props.room.id, catId])" class="flex flex-col items-center justify-center p-4 text-base font-normal text-center transition-colors border-2 rounded-full border-slate-600 hover:border-orange-500 hover:bg-slate-900">
                            {{ cat.name }}
                            <div class="text-xs font-light text-slate-400">
                                {{ cat.equip_count === 1 ? cat.equip_count + ' elemento' : cat.equip_count === 0 ? 'nessun elemento' : cat.equip_count + ' elementi' }}
                            </div>
                        </Link>
                    </div>
                </template>
            </FormElement>
        </template>
    </ContentLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';

const props = defineProps({
    room: Object,
    categories: Object,
});

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
                route: 'sale-prova.equipment.index',
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
        title: 'Equipaggiamento',
    }, {default: () => page}),
};
</script>
