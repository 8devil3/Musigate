<template>
    <Button type="submit" text="Salva" color="green" :icon="props.icon" :disabled="props.isLoading" :isLoading="props.isLoading" />
</template>

<script setup>
import Button from '@/Components/Form/Button.vue'

const props = defineProps({
    onSuccess: Boolean,
    onError: Boolean,
    isLoading: Boolean,
    icon: {
        type: String,
        default: 'fa-solid fa-floppy-disk'
    }
});

</script>