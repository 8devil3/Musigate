<template>
    <label v-if="props.label" :for="props.id" class="block w-full px-3 mb-1.5 text-xs font-medium leading-tight text-left text-white truncate disable:text-slate-400">
        {{ props.label }}
        <!-- <span v-show="props.required" class="text-sm leading-none text-red-500">*</span> -->
    </label>
</template>

<script setup>
const props = defineProps({
    label: String,
    required: Boolean,
    id: String
});

</script>
