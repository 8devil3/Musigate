<template>
    <Head :title="props.title" />

    <div class="flex flex-col grow" :class="route().current('home') ? 'bg-gray-950 bg-opacity-30' : 'bg-gray-950 bg-opacity-60'">
        <Header :class="!route().current('home') ? 'bg-gray-950/30 border-b border-gray-800' : ''" />
    
        <main class="flex flex-col grow">
            <slot />
        </main>
    
        <Footer />
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