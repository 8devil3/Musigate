<template>
    <Head :title="props.title" />

    <div class="flex flex-col min-h-dvh">
        <Header />
    
        <main class="grow radial-gradient">
            <slot />
        </main>
    
        <Footer v-if="!route().current('studio.index')" />
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Frontoffice/Header.vue';
import Footer from '@/Components/Frontoffice/Footer.vue';

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
});

</script>