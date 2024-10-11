<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Contatti"
        icon="fa-solid fa-envelope"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Inserisci i canali con cui gli artisti possono contattare lo Studio.
                </template>
                
                <template #content>
                    <div class="space-y-4 md:space-y-6">
                        <div class="flex items-center gap-4" title="Email">
                            <i class="w-6 text-xl text-center fa-solid fa-envelope"></i>
                            <Input type="email" id="studio-contact-email" v-model="form.email" :error="form.errors.email" placeholder="info@esempio.it" class="w-full md:max-w-lg" />
                        </div>

                        <div class="flex items-center gap-4" title="Telefono">
                            <i class="w-6 text-xl text-center fa-solid fa-phone"></i>
                            <Input type="tel" id="studio-contact-phone" v-model="form.phone" :error="form.errors.phone" placeholder="021234567" class="w-full md:max-w-lg" />
                        </div>

                        <div class="flex items-center gap-4" title="Telegram">
                            <i class="w-6 text-xl text-center fa-brands fa-telegram text-telegram"></i>
                            <Input type="url" id="studio-contact-telegram" v-model="form.telegram" :error="form.errors.telegram" placeholder="https://t.me/USERNAME" class="w-full md:max-w-lg" />
                        </div>

                        <div class="flex items-center gap-4" title="Messenger">
                            <i class="w-6 text-xl text-center fa-brands fa-facebook-messenger text-messenger"></i>
                            <Input type="url" id="studio-contact-messenger" v-model="form.messenger" :error="form.errors.messenger" placeholder="https://m.me/NOME.UTENTE" class="w-full md:max-w-lg" />
                        </div>

                        <div class="flex items-center gap-4" title="Whatsapp">
                            <i class="w-6 text-xl text-center fa-brands fa-whatsapp text-whatsapp"></i>
                            <Input type="url" id="studio-contact-whatsapp" v-model="form.whatsapp" :error="form.errors.whatsapp" placeholder="https://wa.me/TUO-NUMERO-DI-TELEFONO" class="w-full md:max-w-lg" />
                        </div>
                    </div>
                </template>
            </FormElement>
        </template>
        
        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    contacts: Object,
})

const form = useForm({
    email: props.contacts.email,
    phone: props.contacts.phone,
    telegram: props.contacts.telegram,
    messenger: props.contacts.messenger,
    whatsapp: props.contacts.whatsapp,
});

const submit = () => {
    form.put(route('studio.contacts.update'), {
        preserveState: false,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Contatti',
    }, {default: () => page}),
};
</script>
