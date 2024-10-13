<template>
    <div class="flex items-start gap-1.5">
        <input
            type="checkbox"
            v-model="vModel"
            :value="props.value"
            :id="props.id"
            :required="props.required"
            class="size-4 shadow-sm md:mt-[1px] rounded-[4px] border-2 border-orange-500 bg-slate-900 text-orange-500 cursor-pointer"
        >
        <label v-if="$slot"  :for="props.id" class="text-xs leading-none text-gray-300 cursor-pointer md:text-sm mt-[2px] md:mt-0">
            <slot />
        </label>
    </div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: String,
        default: '',
    },
    value: {
        type: [Array, String, Number],
        default: null,
    },
    required: {
        type: Boolean,
        default: false
    }
});

const vModel = defineModel();

</script>
