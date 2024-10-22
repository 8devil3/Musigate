<template>
    <div class="flex flex-wrap pb-8 border-b border-slate-600 lg:border-slate-800 last-of-type:pb-0 last-of-type:border-b-0">
        <div class="w-full md:w-2/5">
            <h3 v-if="$slots.title" class="mb-1 text-sm">
                <slot name="title" />
                <span v-show="props.optional">(opzionale)</span>
            </h3>
            <div v-if="$slots.description" class="text-sm text-slate-300">
                <slot name="description" />
            </div>

            <slot name="subDescription" />
        </div>

        <div v-if="$slots.content" class="w-full mt-4 text-sm text-slate-200 md:pl-12 md:w-2/5 grow md:mt-0">
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
