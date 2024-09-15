<template>
    <BackofficeLayout @submitted="submit()" :isLoading="form.processing" :onSuccess="form.recentlySuccessful" :onFail="form.hasErrors" title="Servizi e comforts" icon="fa-solid fa-hand-holding-heart" :backRoute="route('studio.links')">
        <template #content>
            <!-- servizi -->
            <FormElement>
                <template #title>
                    Servizi
                </template>

                <template #description>
                    Spunta i servizi offerti dal tuo Studio.
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
        </template>
        
        <template #actions>
            <SaveButton />
        </template>
    </BackofficeLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Form/Checkbox.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';

const props = defineProps({
    all_services: Object,
    services: Object,
    all_comforts: Object,
    comforts: Object,
})

const form = useForm({
    services: props.services,
    comforts: props.comforts,
});

const submit = () => {
    form.put(route('studio.servicescomforts.update'), {
        preserveScroll: true,
    });
};

</script>
