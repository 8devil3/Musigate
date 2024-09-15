<template>
    <BackofficeLayout as="div" title="Studio" icon="fa-solid fa-record-vinyl">
        <template #title>
            Studio
        </template>

        <template #content>
            <ul class="flex flex-col items-center justify-center gap-3 mt-6 text-center">
                <li v-for="link in links">
                    <Link :href="link.href" class="flex items-center justify-center w-64 gap-2 px-4 py-1 text-base text-white transition-colors bg-transparent border-2 border-orange-500 rounded-full cursor-pointer hover:border-orange-500 hover:text-white focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 whitespace-nowrap">
                        <i :class="link.icon"></i>
                        {{ link.name }}
                    </Link>
                </li>
            </ul>
        </template>
    </BackofficeLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';

const links = [
    {
        name: 'Generale',
        href: route('studio.general.edit'),
        active: route().current('studio.general.edit'),
        icon: 'fa-solid fa-file-lines'
    },
    {
        name: 'Location',
        href: route('studio.location.edit'),
        active: route().current('studio.location.edit'),
        icon: 'fa-solid fa-location-dot'
    },
    {
        name: 'Foto',
        href: route('studio.photos.edit'),
        active: route().current('studio.photos.edit'),
        icon: 'fa-solid fa-image'
    },
    {
        name: 'Video',
        href: route('studio.videos.edit'),
        active: route().current('studio.videos.edit'),
        icon: 'fa-brands fa-youtube'
    },
    {
        name: 'Metodi di pagamento',
        href: route('studio.payment_methods.edit'),
        active: route().current('studio.payment_methods.edit'),
        icon: 'fa-regular fa-credit-card'
    },
    {
        name: 'Collaborazioni',
        href: route('studio.collaborations.index'),
        active: route().current('studio.collaborations.index'),
        icon: 'fa-regular fa-handshake'
    },
    {
        name: 'Regolamento',
        href: route('studio.rules.edit'),
        active: route().current('studio.rules.edit'),
        icon: 'fa-solid fa-scroll'
    },
    {
        name: 'Servizi e comfort',
        href: route('studio.servicescomforts.edit'),
        active: route().current('studio.servicescomforts.edit'),
        icon: 'fa-solid fa-hand-holding-heart'
    },
    {
        name: 'Social',
        href: route('studio.socials.edit'),
        active: route().current('studio.socials.edit'),
        icon: 'fa-solid fa-share-nodes'
    },
    {
        name: 'Contatti',
        href: route('studio.contacts.edit'),
        active: route().current('studio.contacts.edit'),
        icon: 'fa-solid fa-envelope'
    },
];

</script>
