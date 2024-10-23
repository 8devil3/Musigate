<template>
    <ContentLayout
        @submitted="submit()"
        title="Collaborazioni"
        icon="fa-solid fa-handshake"
        backRoute="studio.collaborazioni.index"
    >
        <template #content>
            <!-- titolo -->
            <FormElement>
                <template #title>
                    Titolo
                </template>
                <template #description>
                    Inserisci il titolo con il nome del collaboratore.
                </template>

                <template #content>
                    <Input v-model="form.title" placeholder="Titolo della collaborazione" :error="form.errors.title" required class="max-w-sm" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- mese/anno -->
            <FormElement>
                <template #title>
                    Mese e anno
                </template>
                <template #description>
                    Inserisci il mese e l'anno della collaborazione.
                </template>

                <template #content>
                    <div class="space-x-2">
                        <Select v-model="form.month" :options="props.months" default="Mese" :error="form.errors.month" required class="w-32" />
                        <Select v-model="form.year" isArray :options="years" default="Anno" :error="form.errors.year" required class="w-24" />
                    </div>
                </template>
            </FormElement>
            <!-- / -->
            
            <!-- links -->
            <FormElement optional>
                <template #title>
                    Link
                </template>
                <template #description>
                    Inserisci i link alle piattaforme musicali.
                </template>

                <template #content>
                    <div class="space-y-4">
                        <div v-for="social, key in socials" :title="social.name" class="flex items-center gap-4">
                            <i :class="[social.icon, social.color]" class="w-6 text-xl text-center"></i>
                            <Input type="url" v-model="form[key]" :placeholder="social.placeholder" :error="form.errors[key]" class="w-full" />
                        </div>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- descrizione -->
            <FormElement optional>
                <template #title>
                    Descrizione
                </template>
                <template #description>
                    Inserisci la descrizione della collaborazione.
                </template>

                <template #content>
                    <Textarea v-model="form.description" placeholder="La descrizione della collaborazione" :error="form.errors.description" class="w-full" />
                </template>
            </FormElement>
            <!-- / -->
        </template>

        <template v-if="form.isDirty && !form.processing" #actions>
            <SaveButton />
            <SaveButton v-if="!props.collaboration" @click="form.save_and_new = true" text="Salva e nuova" icon="fa-solid fa-plus" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Input from '@/Components/Form/Input.vue';
import Button from '@/Components/Form/Button.vue';
import Select from '@/Components/Form/Select.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';

const props = defineProps({
    collaboration: Object,
    months: Object,
});

const form = useForm({
    title: props.collaboration?.title ?? null,
    month: props.collaboration?.month ?? '',
    year: props.collaboration?.year ?? '',
    description: props.collaboration?.description ?? null,
    spotify: props.collaboration?.spotify ?? null,
    soundcloud: props.collaboration?.soundcloud ?? null,
    itunes: props.collaboration?.itunes ?? null,
    save_and_new: false,
});

const submit = ()=>{
    if(form.processing) return;

    if(props.collaboration?.id){
        form.put(route('studio.collaborazioni.update', props.collaboration.id));
    } else {
        form.post(route('studio.collaborazioni.store'), {
            onSuccess: ()=> {
                if(form.save_and_new)form.reset()
            }
        });
    }
};

const years = computed(()=>{
    let years = [];
    let currentYear = parseInt(new Date().getFullYear());

    for (let y = 1900; y <= currentYear; y++) {
        years.unshift(y);
    }

    return years;
});

const socials = {
    spotify: {
        name: 'Spotify',
        placeholder: 'https://open.spotify.com',
        icon: 'fa-brands fa-spotify',
        color: 'text-spotify'
    },
    soundcloud: {
        name: 'SoundCloud',
        placeholder: 'https://soundcloud.com',
        icon: 'fa-brands fa-soundcloud',
        color: 'text-soundcloud'
    },
    itunes: {
        name: 'iTunes',
        placeholder: 'https://itunes.apple.com',
        icon: 'fa-brands fa-itunes',
        color: 'text-itunes'
    },
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Collaborazioni',
    }, {default: () => page}),
};
</script>
