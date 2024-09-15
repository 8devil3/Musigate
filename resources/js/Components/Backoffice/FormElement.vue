<template>
    <div class="grid grid-cols-1 pb-8 border-b border-gray-600 lg:border-gray-800 last-of-type:pb-0 lg:gap-8 lg:grid-cols-12 last-of-type:border-b-0">
        <div class="lg:col-span-5">
            <h3 v-if="$slots.title" class="mb-1 text-base">
                <slot name="title" />
                <span v-show="props.required" class="text-sm text-red-500">*</span>
            </h3>
            <p v-if="$slots.description" class="text-sm text-gray-300/80">
                <slot name="description" />
            </p>

            <slot name="subDescription" />
        </div>

        <div v-if="$slots.content" class="self-start mt-4 text-sm text-gray-200 lg:col-span-7 lg:mt-0">
            <slot name="content" />
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    required: {
        type: Boolean,
        default: false
    }
});
</script>
