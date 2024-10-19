<template>
    <Head :title="props.title"/>
    
    <div class="flex w-full overflow-clip h-dvh">        
        <AsideMenu class="hidden lg:flex" />
        
        <main class="w-full max-w-6xl mx-auto overflow-hidden grow">
            <slot />
        </main>
    </div>

    <!-- messaggi flash -->
    <FlashMessage />
    <!-- / -->
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const props = defineProps({
    title: String,
});

window.addEventListener('popstate', (event) => {
    event.stopImmediatePropagation();

    router.reload({
        replace: true,
        onSuccess: (page) => router.setPage(page, {
            preserveScroll : true,
        }),
        onError: () => window.location.href = event.state.url,
    });
});

</script>