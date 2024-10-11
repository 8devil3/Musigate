<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Social"
        icon="fa-solid fa-share-nodes"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Inserisci i link dei social a cui è iscritto il tuo Studio e il sito web.<br>
                    Tutti i link devono iniziare con <strong class="font-semibold">https://...</strong>
                </template>
        
                <template #content>
                    <div class="space-y-4 md:space-y-6">
                        <div v-for="social, key in socials" :title="social.name" class="flex items-center gap-4">
                            <i :class="[social.icon, social.color]" class="w-6 text-xl text-center"></i>
                            <Input type="url" :id="'studio-social-' + key" v-model="form[key]" :error="form.errors[key]" :placeholder="social.placeholder" class="w-full md:max-w-lg" />
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
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Input from '@/Components/Form/Input.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';

const props = defineProps({
    socials: Object,
});

const form = useForm({
    facebook: props.socials.facebook,
    instagram:  props.socials.instagram,
    youtube: props.socials.youtube,
    soundcloud: props.socials.soundcloud,
    spotify: props.socials.spotify,
    itunes: props.socials.itunes,
    linkedin: props.socials.linkedin,
    website: props.socials.website 
});

const submit = () => {
    form.put(route('studio.socials.update'), {
        preserveState: false,
    });
};

const socials = {
    facebook: {
        name: 'Facebook',
        placeholder: 'https://it.facebook.com',
        icon: 'fa-brands fa-facebook',
        color: 'text-[#1877F2]'
    },
    instagram: { 
        name: 'Instagram',
        placeholder: 'https://www.instagram.com',
        icon: 'fa-brands fa-instagram',
        color: 'text-[#E4405F]'
    },
    youtube: {
        name: 'YouTube',
        placeholder: 'https://www.youtube.com',
        icon: 'fa-brands fa-youtube',
        color: 'text-[#CD201F]'
    },
    soundcloud: {
        name: 'SoundCloud',
        placeholder: 'https://soundcloud.com',
        icon: 'fa-brands fa-soundcloud',
        color: 'text-[#FF3300]'
    },
    spotify: {
        name: 'Spotify',
        placeholder: 'https://open.spotify.com',
        icon: 'fa-brands fa-spotify',
        color: 'text-[#25D865]'
    },
    itunes: {
        name: 'iTunes',
        placeholder: 'https://itunes.apple.com',
        icon: 'fa-brands fa-itunes',
        color: 'text-[#8F60FF]'
    },
    linkedin: {
        name: 'LinkedIn',
        placeholder: 'https://www.linkedin.com',
        icon: 'fa-brands fa-linkedin',
        color: 'text-[#0A66C2]'
    },
    website: {
        name: 'Sito web',
        placeholder: 'https://www.esempio.it',
        icon: 'fa-solid fa-globe',
        color: null
    }
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Social',
    }, {default: () => page}),
};
</script>
