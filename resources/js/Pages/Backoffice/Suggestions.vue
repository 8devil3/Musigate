<template>
    <ContentLayout
    @submitted="submit()"
    :isLoading="form.processing"
    title="Segnalazioni"
    icon="fa-solid fa-flag"
    :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #title>
                    Segnalazioni e suggerimenti
                </template>

                <template #description>
                    Puoi inviare delle segnalazioni o suggerimenti direttamente all'amministrazione di Musigate.<br>
                    Compila i campi e invia il messaggio.
                </template>
                
                <template #content>
                    <div class="space-y-4 md:space-y-6">
                        <Input id="studio-suggestion-title" v-model="form.title" placeholder="Oggetto" required />
                        <Textarea id="studio-suggestion-message" v-model="form.message" placeholder="Messaggio" required />
                        <Button type="submit" text="Invia" icon="fa-solid fa-paper-plane" />
                        <div v-if="form.recentlySuccessful" class="text-green-500">
                            <i class="mr-1 fa-solid fa-check"></i>
                            Messaggio inviato correttamente.
                        </div>
                        <div v-if="form.hasErrors" class="text-red-500">
                            <i class="mr-1 fa-solid fa-xmark"></i>
                            Invio non riuscito!
                        </div>
                    </div>
                </template>
            </FormElement>
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Button from '@/Components/Form/Button.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const form = useForm({
    message: null,
    title: null,
});

const submit = () => {
    form.post(route('suggestions.store'), {
        preserveScroll: true,
        onSuccess: ()=>{
            form.reset();
        }
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Segnalazioni',
    }, {default: () => page}),
};
</script>
