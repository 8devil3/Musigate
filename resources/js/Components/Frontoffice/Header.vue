<template>
    <header class="p-4 lg:px-6" :class="{'bg-slate-950/80 border-b border-slate-700': !route().current('home')}">
        <!-- desktop -->
        <nav class="hidden text-sm tracking-wide lg:gap-6 lg:items-center lg:justify-between lg:flex font-lemon">
            <!-- logo -->
            <Link :href="route('home')" class="shrink-0">
                <img src="/img/logo/logo_horizontal_complete.svg" alt="Musigate logo" class="block h-10 drop-shadow-sm">
            </Link>
            <!-- / -->

            <div class="flex gap-6 lg:gap-8">
                <Link :href="route('studio.index', {category: 'Professional'})" class="uppercase link">
                    Professional studio
                </Link>

                <Link :href="route('studio.index', {category: 'Home'})" class="uppercase link">
                    Home studio
                </Link>
            </div>

            <div v-if="!usePage().props.auth.user" class="flex items-center gap-4 shrink-0">
                <Link :href="route('register.studio.starter.step_1')" class="uppercase link">Registra il tuo Studio</Link>
                <Link :href="route('login')" title="Accesso">
                    <Avatar />
                </Link>
            </div>
            
            <UserMenu v-else />
        </nav>
        <!-- / -->

        <!-- mobile -->
        <nav class="flex items-center justify-between w-full gap-4 lg:hidden">
            <Link :href="route('home')" class="shrink-0">
                <div class="drop-shadow">
                    <img src="/img/logo/logo_mobile.svg" alt="Musigate logo" class="block size-8">
                </div>
            </Link>

            <button v-if="!usePage().props.auth.user" type="button" @click="isOpenDrawer = true" class="py-1 pr-2 text-2xl leading-none text-white">
                <i class="fa-solid fa-bars" />
            </button>

            <UserMenu v-else />
        </nav>
        <!--  -->

        <Drawer :isOpen="isOpenDrawer" @close="isOpenDrawer = false">
            <nav class="flex flex-col w-64 gap-2 py-6">
                <img src="/img/logo/logo_horizontal_complete.svg" alt="Musigate logo" class="mb-8 h-9">
                <Link :href="route('studio.index', {category: 'Professional'})" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                    <i class="w-5 mr-2 text-center fa-solid fa-microphone-lines" />
                    Professional studio
                </Link>
                <Link :href="route('studio.index', {category: 'Home'})" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                    <i class="w-5 mr-2 text-center fa-solid fa-headphones-simple" />
                    Home studio
                </Link>

                <hr class="h-0 mx-4 my-2 border-t border-t-slate-700">

                <Link :href="route('register.studio.starter.step_1')" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                    <i class="w-5 mr-2 text-center fa-solid fa-record-vinyl" />
                    Registra il tuo Studio
                </Link>
                <Link :href="route('login')" class="block px-4 py-2 text-white transition-colors hover:bg-orange-500">
                    <i class="w-5 mr-2 text-center fa-solid fa-right-to-bracket" />
                    Accesso
                </Link>
            </nav>
        </Drawer>
    </header>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Avatar from '@/Components/Avatar.vue';
import Drawer from '@/Components/Drawer.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const isOpenDrawer = ref(false);

</script>
