<template>
    <Button type="submit" text="Salva" title="Salva" color="green" :icon="props.icon" :disabled="props.isLoading" :isLoading="props.isLoading" />
</template>

<script setup>
import Button from '@/Components/Form/Button.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';

const props = defineProps({
    isLoading: Boolean,
    icon: {
        type: String,
        default: 'fa-solid fa-floppy-disk'
    }
});

</script>