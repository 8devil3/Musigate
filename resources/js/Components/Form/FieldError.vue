<template>
    <div v-show="props.error" class="px-4 mt-1 text-sm text-left text-red-500">{{ props.error }}</div>
</template>

<script setup>
const props = defineProps({
    error: String
});

</script>
