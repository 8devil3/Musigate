<template>
    <FrontofficeLayout title="Privacy">
        <div class="w-full max-w-3xl px-4 py-10 mx-auto legal md:px-6 md:py-16">
            <div v-if="props.privacy" v-html="props.privacy"></div>
            <div v-if="props.tos" v-html="props.tos"></div>
            <div v-if="props.cookie" v-html="props.cookie"></div>
        </div>
    </FrontofficeLayout>
</template>

<script setup>
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';

const props = defineProps({
    privacy: String,
    tos: String,
    cookie: String,
});

</script>

<style scoped>
.legal {
    :deep() ul, :deep() ol {
        @apply py-2 pl-5 list-outside;
    }

    :deep() ul li, :deep() ol li {
        @apply relative mb-0.5 last-of-type:mb-0;
    }
    
    :deep() ul {
        @apply list-disc;
    }

    :deep() li::marker {
        @apply text-orange-500 font-semibold tracking-widest;
    }

    :deep() h1 {
        @apply text-3xl mb-8 font-lemon border-b border-orange-500 pb-2;
    }

    :deep() h2, :deep() h3, :deep() h4, :deep() h5, :deep() h6 {
        @apply mb-2 mt-6 font-semibold font-lemon;
    }
}

</style>
