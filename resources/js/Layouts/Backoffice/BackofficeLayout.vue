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