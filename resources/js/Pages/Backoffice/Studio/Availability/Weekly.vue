<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        title="Disponibilità"
        icon="fa-solid fa-clock"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Gestisci l'orario settimanale dello Studio.
                </template>
                
                <template #content>
                    <div class="mb-6">
                        <Toggle
                            v-model="form.is_open_24_7"
                            label="Sempre aperto (24/7)"
                        />
                    </div>

                    <fieldset :disabled="form.is_open_24_7" class="w-full overflow-x-auto disabled:cursor-not-allowed" :class="{'opacity-50' : form.is_open_24_7}">
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="p-2 uppercase bg-slate-800">Giorno</th>
                                    <th class="p-2 uppercase bg-slate-800">Aperto</th>
                                    <th class="p-2 uppercase bg-slate-800">Dalle ore</th>
                                    <th class="p-2 uppercase bg-slate-800">Alle ore</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="wd, key in weekdays">
                                    <td class="w-32 px-2 py-3 text-center" :class="{'text-slate-400' : !form.availability[key].is_open}">
                                        {{ wd }}
                                        <div v-if="!form.availability[key].is_open" class="text-xs text-red-600">chiuso</div>
                                        <div v-else class="text-xs text-green-600">aperto</div>
                                    </td>
                                    <td class="w-20 px-2 py-3 text-center">
                                        <Toggle @click="toggleIsOpen(key)" v-model="form.availability[key].is_open" />
                                    </td>
                                    <td class="w-32 px-2 py-3 text-center">
                                        <Select @change="checkHours(key)" v-model="form.availability[key].start" isArray :options="hours" :error="form.errors['availability.' + key + '.start']" default="Dalle ore" :disabled="!form.availability[key].is_open" class="w-32" required />
                                    </td>
                                    <td class="w-32 px-2 py-3 text-center">
                                        <Select @change="checkHours(key)" v-model="form.availability[key].end" isArray :options="hours" :error="form.errors['availability.' + key + '.end']" default="Alle ore" :disabled="!form.availability[key].is_open" class="w-32" required />
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </fieldset>
                </template>
            </FormElement>
        </template>
        
        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Select from '@/Components/Form/Select.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    availability: Object,
    is_open_24_7: Boolean,
});

const form = useForm({
    is_open_24_7: props.is_open_24_7,
    availability: props.availability,
});

const submit = () => {
    if(form.processing) return;
    form.put(route('studio.availability.update'), {
        preserveScroll: true,
    });
};

const toggleIsOpen = (key)=>{
    if(!form.availability[key].is_open){
        form.availability[key].start = '';
        form.availability[key].end = '';
    }
};

const checkHours = (key)=>{
    if((form.availability[key].start && form.availability[key].end)){
        if(form.availability[key].end <= form.availability[key].start){
            form.errors['availability.' + key + '.start'] = 'Non valido';
            form.errors['availability.' + key + '.end'] = 'Non valido';
        } else {
            form.errors['availability.' + key + '.start'] = null;
            form.errors['availability.' + key + '.end'] = null;
        }
    }
};

const hours = computed(()=>{
    let arrHours = [];

    for (let i = 0; i < 24; i++) {
        if(i < 10){
            arrHours.push('0' + i + ':00');
            arrHours.push('0' + i + ':30')
        }
        else{
            arrHours.push(i + ':00');
            arrHours.push(i + ':30');
        }
    }

    arrHours.push('24:00');

    return arrHours;
});

const weekdays = {
    1: 'Lunedì',
    2: 'Martedì',
    3: 'Mercoledì',
    4: 'Giovedì',
    5: 'Venerdì',
    6: 'Sabato',
    7: 'Domenica',
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità',
    }, {default: () => page}),
};
</script>
