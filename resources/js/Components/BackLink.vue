<template>
    <Link :href="props.href" class="text-sm font-medium text-orange-500 transition-colors hover:text-orange-400">
        <i class="mr-2 fa-solid fa-arrow-left"></i>
        {{ props.label }}
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    label: {
        type: String,
        default: 'Torna indietro'
    },
});

</script>
