<template>
    <Head :title="props.title" />

    <div class="flex flex-col bg-no-repeat scroll-pt-14 md:scroll-pt-16 bg-cover bg-[url(/public/img/banner_home.jpg)] bg-center" :class="route().current('home') ? 'bg-gray-950 bg-opacity-30' : 'bg-gray-950 bg-opacity-60'">
        <Header />
    
        <main class="h-[calc(100dvh-73px)]">
            <slot />
        </main>
    
        <!-- <Footer v-if="!route().current('studio.index')" /> -->
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