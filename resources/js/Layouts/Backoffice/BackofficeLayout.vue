<template>
    <Head :title="props.title"/>

    <Header />
    
    <div class="flex h-[calc(100dvh-64px)] overflow-hidden lg:bg-slate-950 w-full grow">        
        <AsideMenu />
        
        <main class="w-full max-w-6xl mx-auto overflow-hidden grow lg:p-8">
            <slot />
        </main>
    </div>

    <!-- app bar -->
    <!-- <AppBar /> -->
    <!-- / -->

    <!-- messaggi flash e spinner -->
    <!-- <div class="fixed -translate-x-1/2 top-9 lg:top-4 left-1/2 z-[2000]">
        <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="props.onSuccess" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold leading-none text-white rounded-lg shadow-lg bg-emerald-500">
                <i class="fa-solid fa-check"></i>
                {{ props.flashMessage }}
            </div>
        </transition>

        <div v-show="props.onFail" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold leading-none text-white bg-red-600 rounded-lg shadow-lg">
            <i class="fa-solid fa-xmark"></i>
            Non riuscito!
        </div>
    </div>

    <div v-if="props.isLoading" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/30">
        <Spinner class="w-16 h-16 orange"/>
    </div> -->
    <!-- / -->
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Spinner from '@/Components/Spinner.vue';
import Header from '@/Components/Backoffice/Header.vue';
import AppBar from '@/Components/Backoffice/AppBar.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const props = defineProps({
    title: String,
});

</script>