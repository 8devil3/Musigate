<template>
    <header class="p-4 md:p-6">
        <div class="flex items-center justify-between w-full mx-auto text-sm tracking-wide font-lemon max-w-[1536px]">
            <!-- logo -->
            <Link :href="route('home')" class="shrink-0">
                <img src="/img/logo/logo_horizontal.svg" alt="Musigate logo" class="hidden md:block md:h-7">
                <img src="/img/logo/logo_mobile.svg" alt="Musigate logo" class="h-6 md:hidden">
            </Link>
            <!-- / -->

            <div class="flex items-center gap-4 uppercase">
                <!-- <a href="#" class="py-2 link">About</a> -->
                <a href="https://blog.musigate.it" target="_blank" class="py-2 link">Blog</a>
            </div>

            <!-- desktop -->
            <nav class="hidden md:gap-6 md:items-center md:justify-between md:flex">
                <template v-if="!$page.props.auth.user">
                    <Link :href="route('register.create')" class="uppercase link">Registra il tuo Studio</Link>
                    <Link :href="route('login.create')" title="Accesso">
                        <Avatar />
                    </Link>
                </template>
                
                <UserMenu v-else />
            </nav>
            <!-- / -->

            <!-- mobile -->
            <nav class="flex items-center gap-4 md:hidden">
                <Link v-if="!$page.props.auth.user" :href="route('login.create')">
                    <Avatar />
                </Link>
                
                <UserMenu v-else />
            </nav>
            <!--  -->
        </div>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Avatar from '@/Components/Avatar.vue';

</script>
