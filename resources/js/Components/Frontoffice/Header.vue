<template>
    <header class="p-4 lg:px-6" :class="{'bg-slate-950/80 border-b border-slate-700': !route().current('home')}">
        <div class="flex items-center justify-between w-full mx-auto text-sm tracking-wide font-lemon">



            <!-- logo -->
            <Link :href="route('home')" class="hidden shrink-0 md:block">
                <img src="/img/logo/logo_horizontal_complete.svg" alt="Musigate logo" class="block h-10 drop-shadow-sm">
            </Link>
            <!-- / -->

            <!-- desktop -->
            <nav class="hidden md:gap-6 md:items-center md:justify-between md:flex">
                <template v-if="!usePage().props.auth.user">
                    <Link :href="route('register.studio.starter.step_1')" class="uppercase link">Registra il tuo Studio</Link>
                    <Link :href="route('login')" title="Accesso">
                        <Avatar />
                    </Link>
                </template>
                
                <UserMenu v-else />
            </nav>
            <!-- / -->

            <!-- mobile -->
            <nav class="flex items-center justify-between w-full gap-4 md:hidden">
                <Link :href="route('home')" class="shrink-0">
                    <div class="drop-shadow">
                        <img src="/img/logo/logo_mobile.svg" alt="Musigate logo" class="block size-8">
                    </div>
                </Link>

                <button type="button" @click="isOpenDrawer = true" class="py-1 pr-2 text-2xl leading-none text-white md:hidden">
                    <i class="fa-solid fa-bars" />
                </button>
            </nav>
            <!--  -->
        </div>
    </header>

    <Drawer :isOpen="isOpenDrawer" @close="isOpenDrawer = false">
        <nav v-if="!usePage().props.auth.user" class="flex flex-col gap-2 py-6">
            <img src="/img/logo/logo_horizontal.svg" alt="Musigate logo" class="h-4 mb-6">
            <Link :href="route('register.studio.starter.step_1')" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                <i class="w-5 mr-2 fa-solid fa-record-vinyl" />
                Registra il tuo Studio
            </Link>
            <Link :href="route('login')" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                <i class="w-5 mr-2 fa-solid fa-right-to-bracket" />
                Accesso
            </Link>
        </nav>

        <AsideMenu v-else />
    </Drawer>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Avatar from '@/Components/Avatar.vue';
import Drawer from '@/Components/Drawer.vue';

const isOpenDrawer = ref(false);

</script>
