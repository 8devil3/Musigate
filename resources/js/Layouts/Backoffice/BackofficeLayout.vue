<template>
    <Head :title="props.title"/>
    
    <div class="flex w-full overflow-clip h-dvh">        
        <AsideMenu class="hidden lg:flex" />
        
        <main class="w-full max-w-6xl mx-auto overflow-hidden grow">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const props = defineProps({
    title: String,
});


</script>