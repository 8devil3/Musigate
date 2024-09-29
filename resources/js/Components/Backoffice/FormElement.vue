<template>
    <div class="grid grid-cols-1 pb-8 border-b border-slate-600 lg:border-slate-800 last-of-type:pb-0 lg:gap-12 lg:grid-cols-12 last-of-type:border-b-0">
        <div class="lg:col-span-5">
            <h3 v-if="$slots.title" class="mb-1 text-sm">
                <slot name="title" />
                <span v-show="props.optional">(opzionale)</span>
            </h3>
            <div v-if="$slots.description" class="text-sm text-slate-300/80">
                <slot name="description" />
            </div>

            <slot name="subDescription" />
        </div>

        <div v-if="$slots.content" class="self-start mt-4 text-sm text-slate-200 lg:col-span-7 lg:mt-0">
            <slot name="content" />
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    optional: {
        type: Boolean,
        default: false
    }
});
</script>
