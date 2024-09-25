<template>
    <nav class="flex flex-col w-64 h-full gap-3 pb-6 overflow-y-auto font-sans font-medium shrink-0 bg-slate-900 lg:gap-10 noscrollbar">
        <hr class="hidden shrink-0 lg:block h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />

        <!-- nome e logo studio -->
        <div class="px-2 py-4 space-y-4 lg:py-0 shrink-0">
            <div class="flex items-center justify-center w-16 mx-auto border-2 border-orange-500 rounded-full aspect-square">
                <img v-if="usePage().props.auth.studio.logo" :src="'/storage/' + usePage().props.auth.studio.logo" :alt="usePage().props.auth.studio.name" class="object-contain w-full h-full rounded-full overflow-clip">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="usePage().props.auth.studio.name" class="object-contain w-8 aspect-square">
            </div>
            <div class="space-y-1 font-sans text-lg tracking-wide text-center">
                <div class="text-xs uppercase text-slate-400">
                    {{ usePage().props.auth.studio.category }} studio
                </div>
                <div class="text-white font-lemon">
                    {{ usePage().props.auth.studio.name }}
                </div>
            </div>
        </div>

        <hr class="lg:hidden shrink-0 h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />

        <!-- menu -->
        <ul class="space-y-2 list-none list-image-none">
            <template v-for="item, index in menu" :key="index">
                <li v-if="item.route && !item.children.length">
                    <Link :href="route(item.route)" class="flex items-center gap-2 px-3 py-2 text-sm transition-colors border-transparent text-slate-300 border-x-4 hover:border-l-orange-500 hover:bg-slate-700/50" :class="[{'border-l-orange-500 hover:bg-transparent text-slate-100 bg-slate-700/80' : route().current(item.active)}]">
                        <i class="w-5 text-center" :class="item.icon" />
                        {{ item.label }}
                    </Link>
                </li>

                <li v-else>
                    <details open @toggle="toggleDetailState(index)">
                        <summary class="flex items-center justify-between px-3 py-2 text-sm transition-colors border-transparent cursor-pointer text-slate-300 border-x-4 hover:bg-slate-700/50" :class="[{'hover:bg-transparent text-slate-100 bg-slate-700/80' : route().current(item.active)}]">
                            <div class="flex items-center gap-2">
                                <i class="w-5 text-center" :class="item.icon" />
                                {{ item.label }}
                            </div>

                            <i class="text-orange-500 transition-transform fa-solid fa-chevron-down" :class="{'rotate-180' : detailState[index]}" />
                        </summary>
                        
                        <ul class="mt-1 ml-6 space-y-1 list-none border-l list-image-none border-slate-600">
                            <li v-for="child in item.children">
                                <Link :href="route(child.route)" class="flex items-center gap-2 px-3 py-1.5 text-xs transition-colors border-transparent text-slate-300 border-x-4 hover:border-l-orange-500 hover:bg-slate-700/50" :class="[{'border-l-orange-500 text-slate-100 bg-slate-700/80' : route().current(child.active)}]">
                                    <i class="w-5 text-center" :class="child.icon" />
                                    {{ child.label }}
                                </Link>
                            </li>
                        </ul>
                    </details>
                </li>
            </template>
        </ul>
        <!-- / -->
    </nav>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const detailState = ref({});

const toggleDetailState = (index)=>{
    detailState.value[index] = !detailState.value[index];
};

const menu = computed(()=>{
    let menu = [];
    let roleId = usePage().props.auth.user.role_id;

    if(roleId === 2){ //menu studio
        menu = [
            {
                icon: 'fa-solid fa-home',
                label: 'Dashboard',
                route: 'dashboard',
                active: 'dashboard',
                children: []
            },
            {
                icon: 'fa-solid fa-record-vinyl',
                label: 'Studio',
                route: null,
                active: 'studio.*',
                children: [
                    {
                        icon: 'fa-solid fa-file-lines',
                        label: 'Generale',
                        route: 'studio.general.edit',
                        active: 'studio.general.edit'
                    },
                    {
                        icon: 'fa-solid fa-location-dot',
                        label: 'Location',
                        route: 'studio.location.edit',
                        active: 'studio.location.edit'
                    },
                    {
                        icon: 'fa-solid fa-clock',
                        label: 'Disponibilità',
                        route: 'studio.availability.edit',
                        active: 'studio.availability.edit'
                    },
                    {
                        icon: 'fa-solid fa-image',
                        label: 'Foto',
                        route: 'studio.photos.edit',
                        active: 'studio.photos.edit'
                    },
                    {
                        icon: 'fa-brands fa-youtube',
                        label: 'Video',
                        route: 'studio.videos.edit',
                        active: 'studio.videos.edit'
                    },
                    {
                        icon: 'fa-regular fa-credit-card',
                        label: 'Metodi di pagamento',
                        route: 'studio.payment_methods.edit',
                        active: 'studio.payment_methods.edit'
                    },
                    {
                        icon: 'fa-solid fa-handshake',
                        label: 'Collaborazioni',
                        route: 'studio.collaborations.index',
                        active: 'studio.collaborations.index'
                    },
                    {
                        icon: 'fa-solid fa-scroll',
                        label: 'Regolamento',
                        route: 'studio.rules.edit',
                        active: 'studio.rules.edit'
                    },
                    {
                        icon: 'fa solid fa-hand-holding-heart',
                        label: 'Servizi e Comforts',
                        route: 'studio.servicescomforts.edit',
                        active: 'studio.servicescomforts.edit'
                    },
                    {
                        icon: 'fa-solid fa-share-nodes',
                        label: 'Social',
                        route: 'studio.socials.edit',
                        active: 'studio.socials.edit'
                    },
                    {
                        icon: 'fa-solid fa-envelope',
                        label: 'Contatti',
                        route: 'studio.contacts.edit',
                        active: 'studio.contacts.edit'
                    },
                ]
            },
            {
                icon: 'fa-solid fa-calendar-days',
                label: 'Prenotazioni',
                route: null,
                active: 'bookings.*',
                children: [
                    {
                        icon: 'fa-solid fa-calendar-days',
                        label: 'Prenotazioni',
                        route: 'bookings.index',
                        active: 'bookings.index'
                    },
                    {
                        icon: 'fa-solid fa-gears',
                        label: 'Impostazioni prenotazioni',
                        route: 'bookings.settings.edit',
                        active: 'bookings.settings.edit'
                    },
                    {
                        icon: 'fa-solid fa-calendar-xmark',
                        label: 'Policy annullamenti',
                        route: 'cancelling.settings.edit',
                        active: 'cancelling.settings.edit'
                    },
                ]
            },
            {
                icon: 'fa-solid fa-microphone-lines',
                label: 'Sale Studio',
                route: 'rooms.index',
                active: 'rooms.*',
                children: []
            },
            {
                icon: 'fa-solid fa-user-gear',
                label: 'Account',
                route: 'account.edit',
                active: 'account.edit',
                children: []
            },
            {
                icon: 'fa-solid fa-flag',
                label: 'Segnalazioni',
                route: 'suggestions.create',
                active: 'suggestions.create',
                children: []
            }
        ];
    } else if(roleId === 3){ //menu artista
        menu = [
            {
                icon: 'fa-solid fa-home',
                label: 'Dashboard',
                route: 'dashboard',
                active: 'dashboard',
                children: []
            },
        ];
    }

    return menu;
});

</script>
