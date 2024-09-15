<template>
    <button type="button" class="show-all">
        {{ props.text }}
        <i class="text-xs fa-solid fa-chevron-right"></i>
    </button>
</template>

<script setup>
const props = defineProps({
    text: {
        type: String,
        default: 'Mostra tutto'
    }
});

</script>
