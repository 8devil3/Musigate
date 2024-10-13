<template>
    <nav class="flex flex-col w-64 h-full font-sans font-medium bg-slate-900 shrink-0 noscrollbar">
        <div class="sticky top-0 shadow-md">
            <Link :href="route('dashboard')" class="w-64 h-14 mt-1.5 flex items-center justify-center">
                <img src="/img/logo/logo_horizontal.svg" alt="Musigate logo" class="h-6">
            </Link>
            <div class="shrink-0 block h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />
        </div>

        <div class="py-6 space-y-6 overflow-y-auto grow">
            <!-- nome e logo studio -->
            <div class="px-2 space-y-4 shrink-0">
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
    
            <!-- menu -->
            <ul class="space-y-2 list-none list-image-none">
                <template v-for="item, index in menu" :key="index">
                    <li v-if="item.route && !item.children.length">
                        <Link :href="route(item.route)" class="flex items-center gap-2 px-4 py-2 text-sm transition-colors hover:text-orange-400" :class="route().current(item.active) ? 'text-white' : 'text-slate-400'">
                            <i class="w-5 text-center" :class="item.icon" />
                            {{ item.label }}
                        </Link>
                    </li>
    
                    <li v-else>
                        <details open @toggle="toggleDetailState(index)">
                            <summary class="flex items-center justify-between px-4 py-2 text-sm transition-colors cursor-pointer hover:text-orange-400" :class="route().current(item.active) ? 'text-slate-200' : 'text-slate-400'">
                                <div class="flex items-center gap-2">
                                    <i class="w-5 text-center" :class="item.icon" />
                                    {{ item.label }}
                                </div>
    
                                <i class="text-orange-500 transition-transform fa-solid fa-chevron-down" :class="{'rotate-180' : detailState[index]}" />
                            </summary>
                            
                            <ul class="mt-1 ml-6 space-y-1 list-none border-l list-image-none border-slate-600">
                                <li v-for="child in item.children">
                                    <Link :href="route(child.route)" class="flex items-center gap-2 px-4 py-1.5 text-xs transition-colors font-normal hover:text-orange-400" :class="route().current(child.active) ? 'text-white' : 'text-slate-400'">
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
        </div>

        <form @submit.prevent="router.post(route('logout'))" class="border-t bg-slate-900 border-slate-700">
            <button type="submit" class="flex items-center w-full gap-2 p-4 text-sm hover:text-orange-400">
                <i class="w-5 fa-solid fa-right-from-bracket" />
                Logo out
            </button>
        </form>
    </nav>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';;

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
                        label: 'Descrizione',
                        route: 'studio.description.edit',
                        active: 'studio.description.edit'
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
                        label: 'Comforts',
                        route: 'studio.comforts.edit',
                        active: 'studio.comforts.edit'
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
            // {
            //     icon: 'fa-solid fa-calendar-days',
            //     label: 'Prenotazioni',
            //     route: null,
            //     active: 'bookings.*',
            //     children: [
            //         {
            //             icon: 'fa-solid fa-calendar-days',
            //             label: 'Calendario',
            //             route: 'bookings.index',
            //             active: 'bookings.index'
            //         },
            //         {
            //             icon: 'fa-solid fa-gears',
            //             label: 'Impostazioni prenotazioni',
            //             route: 'bookings.settings.edit',
            //             active: 'bookings.settings.edit'
            //         },
            //         {
            //             icon: 'fa-solid fa-calendar-xmark',
            //             label: 'Policy annullamenti',
            //             route: 'cancelling.settings.edit',
            //             active: 'cancelling.settings.edit'
            //         },
            //     ]
            // },
            {
                icon: 'fa-solid fa-music',
                label: 'Sale prova',
                route: 'sale-prova.index',
                active: 'sale-prova.*',
                children: []
            },
            {
                icon: 'fa-solid fa-microphone-lines',
                label: 'Servizi',
                route: 'servizi.index',
                active: 'servizi.*',
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
