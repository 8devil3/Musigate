<template>
    <ContentLayout
        as="div"
        title="Disponibilità settimanale"
        icon="fa-solid fa-clock"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Click o tap su un giorno della settimana per impostare o modificare gli orari e le fasce orarie.
                </template>
                <template #content>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <Link v-for="av in props.availability" :href="route('studio.availability.edit', av.id)" class="block p-4 space-y-2 font-normal transition-colors border-2 rounded-2xl border-slate-600 hover:border-orange-500 hover:bg-slate-900">
                            <div class="text-base" :class="av.open_type !== 'close' ? 'text-white' : 'text-slate-400'">
                                {{ props.weekdays[av.weekday] }}
                                <div v-if="av.open_type === 'open'" class="text-xs text-green-500">
                                    aperto
                                </div>
                                <div v-else-if="av.open_type === 'open_h24'" class="text-xs text-sky-500">
                                    aperto h24
                                </div>
                                <div v-else-if="av.open_type === 'close'" class="text-xs text-red-500">
                                    chiuso
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div v-if="!av.timebands.length" class="text-xs text-slate-400">
                                    nessuna fascia oraria
                                </div>
                                <div v-else v-for="timeband in av.timebands" class="flex gap-1 text-xs text-white">
                                    {{ timeband.start }}
                                    -
                                    {{ timeband.end }}
                                    {{ timeband.name }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </template>
            </FormElement>
        </template>
    </ContentLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';

const props = defineProps({
    availability: Object,
    weekdays: Object,
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>
