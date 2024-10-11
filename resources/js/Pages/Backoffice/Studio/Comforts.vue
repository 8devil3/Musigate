<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Comfort"
        icon="fa-solid fa-hand-holding-heart"
        :backRoute="route('studio.links')"
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
        </template>
        
        <template #actions>
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
})

const form = useForm({
    comforts: props.comforts ?? [],
});

const submit = () => {
    form.put(route('studio.comforts.update'), {
        preserveState: false,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Comfort',
    }, {default: () => page}),
};
</script>
