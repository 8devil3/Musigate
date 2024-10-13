<template>
    <Head :title="props.title"/>

    <!-- <Header /> -->
    
    <div class="flex w-full overflow-hidden h-dvh grow">        
        <AsideMenu />
        
        <main class="w-full max-w-6xl mx-auto overflow-hidden grow">
            <slot />
        </main>
    </div>

    <!-- app bar -->
    <!-- <AppBar /> -->
    <!-- / -->
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const props = defineProps({
    title: String,
});

</script>