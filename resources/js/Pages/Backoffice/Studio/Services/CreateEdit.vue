<template>
    <ContentLayout
        :isLoading="form.processing"
        :title="props.service?.name ?? 'Nuovo servizio'"
        icon="fa-solid fa-microphone-lines"
        :backLink="route('servizi.index')"
    >
        <template #content>
            {{ props.service.name }}
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Button from '@/Components/Form/Button.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    service: Object,
});

const form = useForm({
    
});

const submit = ()=>{
    if(form.processing) return;

    if(props.service) form.put(route('servizi.update', props.service.id));
    else form.post(route('servizi.store'));
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Servizi',
    }, {default: () => page}),
};
</script>