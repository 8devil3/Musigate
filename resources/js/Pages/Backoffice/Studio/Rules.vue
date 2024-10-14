<template>
    <ContentLayout
        @submitted="submit()"
        title="Regolamento"
        icon="fa-solid fa-scroll"
    >
        <template #description>
            Inserisci il regolamento dello Studio valido prima, durante e dopo le sessioni.<br>
            Può includere indicazioni sul codice di comportamento e/o condotta nelle tre fasi della sessione in Studio.
        </template>

        <template #content>
            <FormElement>
                <template #title>
                    Prima della sessione
                </template>

                <template #description>
                    Inserisci il regolamento valido prima della sessione.
                </template>

                <template #content>
                    <Textarea v-model="form.before" placeholder="Regolamento prima della sessione" :error="form.errors.before" />
                </template>
            </FormElement>

            <FormElement>
                <template #title>
                    Durante la sessione
                </template>

                <template #description>
                    Inserisci il regolamento valido durante la sessione.
                </template>

                <template #content>
                    <Textarea v-model="form.during" placeholder="Regolamento durante la sessione" :error="form.errors.during" />
                </template>
            </FormElement>

            <FormElement>
                <template #title>
                    Dopo la sessione
                </template>

                <template #description>
                    Inserisci il regolamento valido dopo la sessione.
                </template>

                <template #content>
                    <Textarea v-model="form.after" placeholder="Regolamento dopo la sessione" :error="form.errors.after" />
                </template>
            </FormElement>
        </template>
        
        <template #actions>
            <SaveButton v-if="form.isDirty && !form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    rules: Object,
})

const form = useForm({
    before: props.rules.before,
    during: props.rules.during,
    after: props.rules.after,
});

const submit = () => {
    form.put(route('studio.rules.update'));
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Regolamento',
    }, {default: () => page}),
};
</script>
