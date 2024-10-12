<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Disponibilità settimanale"
        icon="fa-solid fa-business-time"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #title>
                    Orari di apertura
                </template>
                <template #description>
                    Imposta i giorni e gli orari di lavoro dello Studio.
                </template>
                <template #content>
                    <div class="space-y-4 divide-y sm:max-w-sm divide-slate-700 sm:divide-y-0">
                        <div v-for="av in form.availability" class="grid grid-cols-2 pt-4 sm:pt0">
                            <div class="grid grid-cols-1 gap-y-2 gap-x-0 sm:grid-cols-2">
                                <div class="text-center md:text-base sm:text-left">
                                    <div class="text-sm font-normal" :class="av.is_open ? 'text-white' : 'text-slate-400'">
                                        {{ props.weekdays[av.weekday] }}
                                    </div>
                                    <div v-if="av.is_open" class="text-xs font-light text-green-500">
                                        aperto
                                    </div>
                                    <div v-else class="text-xs font-light text-red-500">
                                        chiuso
                                    </div>
                                </div>

                                <Toggle v-model="av.is_open" :disabled="form.processing" class="self-center place-self-center" />
                            </div>

                            <div class="grid w-full grid-cols-1 gap-2 sm:grid-cols-2">
                                <Select
                                    v-model="av.open_start"
                                    isArray
                                    :options="props.hours"
                                    :disabled="!av.is_open"
                                    default="Inizio"
                                    :error="form.errors['availability' + index + 'open_start']"
                                    :required="av.is_open"
                                    class="max-w-24"
                                />

                                <Select
                                    v-model="av.open_end"
                                    isArray
                                    :options="hours.filter(h => h > av.open_start)"
                                    default="Fine"
                                    :disabled="!av.is_open"
                                    :error="form.errors['availability' + index + 'open_end']"
                                    :required="av.is_open"
                                    class="max-w-24"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </FormElement>
        </template>

        <template #actions>
            <SaveButton :disabled="form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';

const props = defineProps({
    availability: Object,
    timebands: Object,
    hours: Object,
    weekdays: Object,
});

const form = useForm({
    availability: props.availability
});

const submit = ()=>{
    if(form.processing) return;
    form.put(route('studio.availability.update'), {
        preserveState: false,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>
