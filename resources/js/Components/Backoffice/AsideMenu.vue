<template>
    <nav class="flex flex-col h-full gap-3 pb-6 overflow-y-auto font-sans font-medium bg-gray-900 lg:gap-10 noscrollbar">
        <hr class="hidden shrink-0 lg:block h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />

        <!-- nome e logo studio -->
        <div class="px-2 py-4 space-y-4 lg:py-0 shrink-0">
            <div class="flex items-center justify-center w-16 mx-auto border-2 border-orange-500 rounded-full aspect-square">
                <img v-if="$page.props.auth.studio.logo" :src="'/storage/' + $page.props.auth.studio.logo" :alt="$page.props.auth.studio.name" class="object-contain w-full h-full rounded-full overflow-clip">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="$page.props.auth.studio.name" class="object-contain w-8 aspect-square">
            </div>
            <div class="space-y-1 font-sans text-lg tracking-wide text-center">
                <div class="text-xs text-gray-400 uppercase">
                    {{ $page.props.auth.studio.category }} studio
                </div>
                <div class="text-white">
                    {{ $page.props.auth.studio.name }}
                </div>
            </div>
        </div>

        <hr class="lg:hidden shrink-0 h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />

        <!-- menu studio -->
        <div class="space-y-1">
            <ul v-if="$page.props.auth.user.role_id === 2" v-for="studioItem in menuItemsStudio">
                <MenuItem v-if="!studioItem.subItems.length" :href="studioItem.href" :text="studioItem.text" :icon="studioItem.icon" :active="studioItem.active" />
                <MenuItem v-else :href="studioItem.href" :text="studioItem.text" :icon="studioItem.icon" isSubmenu :subItems="studioItem.subItems" />
            </ul>
        </div>
        <!-- / -->
    </nav>
</template>

<script setup>
import MenuItem from '@/Components/Backoffice/MenuItem.vue';

const menuItemsStudio = [
    {
        icon: 'fa-solid fa-home',
        text: 'Dashboard',
        href: route('dashboard'),
        active: route().current('dashboard'),
        subItems: []
    },
    {
        icon: 'fa-solid fa-record-vinyl',
        text: 'Studio',
        href: null,
        active: route().current('studio.*'),
        subItems: [
            {
                icon: 'fa-solid fa-file-lines',
                text: 'Generale',
                href: route('studio.general.edit'),
                active: route().current('studio.general.edit')
            },
            {
                icon: 'fa-solid fa-location-dot',
                text: 'Location',
                href: route('studio.location.edit'),
                active: route().current('studio.location.edit')
            },
            {
                icon: 'fa-solid fa-clock',
                text: 'Disponibilità',
                href: route('studio.availability.edit'),
                active: route().current('studio.availability.edit')
            },
            {
                icon: 'fa-solid fa-image',
                text: 'Foto',
                href: route('studio.photos.edit'),
                active: route().current('studio.photos.edit')
            },
            {
                icon: 'fa-brands fa-youtube',
                text: 'Video',
                href: route('studio.videos.edit'),
                active: route().current('studio.videos.edit')
            },
            {
                icon: 'fa-regular fa-credit-card',
                text: 'Metodi di pagamento',
                href: route('studio.payment_methods.edit'),
                active: route().current('studio.payment_methods.edit')
            },
            {
                icon: 'fa-solid fa-handshake',
                text: 'Collaborazioni',
                href: route('studio.collaborations.index'),
                active: route().current('studio.collaborations.index')
            },
            {
                icon: 'fa-solid fa-scroll',
                text: 'Regolamento',
                href: route('studio.rules.edit'),
                active: route().current('studio.rules.edit')
            },
            {
                icon: 'fa solid fa-hand-holding-heart',
                text: 'Servizi e Comforts',
                href: route('studio.servicescomforts.edit'),
                active: route().current('studio.servicescomforts.edit')
            },
            {
                icon: 'fa-solid fa-share-nodes',
                text: 'Social',
                href: route('studio.socials.edit'),
                active: route().current('studio.socials.edit')
            },
            {
                icon: 'fa-solid fa-envelope',
                text: 'Contatti',
                href: route('studio.contacts.edit'),
                active: route().current('studio.contacts.edit')
            },
        ]
    },
    {
        icon: 'fa-solid fa-calendar-days',
        text: 'Prenotazioni',
        href: null,
        active: route().current('bookings.*'),
        subItems: [
            {
                icon: 'fa-solid fa-calendar-days',
                text: 'Prenotazioni',
                href: route('bookings.settings.edit'),
                active: route().current('bookings.settings.edit')
            },
            {
                icon: 'fa-solid fa-gears',
                text: 'Impostazioni',
                href: route('bookings.settings.edit'),
                active: route().current('bookings.settings.edit')
            },
        ]
    },
    {
        icon: 'fa-solid fa-microphone-lines',
        text: 'Sale Studio',
        href: route('rooms.index'),
        active: route().current('rooms.*'),
        subItems: []
    },
    {
        icon: 'fa-solid fa-user-gear',
        text: 'Account',
        href: route('account.edit'),
        active: route().current('account.edit'),
        subItems: []
    },
    {
        icon: 'fa-solid fa-flag',
        text: 'Segnalazioni',
        href: route('suggestions.create'),
        active: route().current('suggestions.create'),
        subItems: []
    }
];

</script>
