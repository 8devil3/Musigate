<template>
    <Link :href="props.router" class="text-sm font-medium text-orange-500 transition-colors hover:text-orange-400">
        <i class="mr-2 fa-solid fa-arrow-left"></i>
        {{ props.text }}
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    text: {
        type: String,
        default: 'Torna indietro'
    },
    router: {
        type: String,
        default: '#'
    }
});

</script>
