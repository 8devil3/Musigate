<template>
    <ContentLayout
        @submitted="submit()"
        title="Comfort e servizi"
        icon="fa-solid fa-hand-holding-heart"
    >
        <template #content>
            <!-- comforts -->
            <FormElement>
                <template #title>
                    Comfort
                </template>

                <template #description>
                    Spunta i comfort offerti dal tuo Studio.
                </template>

                <template #content>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <Checkbox v-for="comfort, key in props.all_comforts" v-model="form.comforts" :value="parseInt(key)" :id="'edit-studio-comfort-' + key">
                            {{ comfort }}
                        </Checkbox>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- services -->
            <FormElement>
                <template #title>
                    Servizi
                </template>

                <template #description>
                    Spunta i servizi offerti dal tuo Studio che sono complementari e agevolano l'attività dell'artista.
                </template>

                <template #content>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <Checkbox v-for="service, key in props.all_services" v-model="form.services" :value="parseInt(key)" :id="'edit-studio-service-' + key">
                            {{ service }}
                        </Checkbox>
                    </div>
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template v-if="form.isDirty && !form.processing" #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Form/Checkbox.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    all_comforts: Object,
    comforts: Object,
    all_services: Object,
    services: Object,
})

const form = useForm({
    comforts: props.comforts ?? [],
    services: props.services ?? [],
});

const submit = () => {
    form.put(route('studio.comforts_services.update'), {
        preserveState: false,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Comfort e servizi',
    }, {default: () => page}),
};
</script>
