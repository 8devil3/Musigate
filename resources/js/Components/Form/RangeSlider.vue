<template>
   <div>
        <input type="range"
            v-model="vModel"
            :min="props.min"
            :max="props.max"
            :step="props.step"
            class="w-full h-1.5 bg-gray-600 rounded-full appearance-none cursor-pointer"
        >
    </div>
</template>

<script setup>
const props = defineProps({
    min: {
        type: Number,
        default: 10
    },
    max: {
        type: Number,
        default: 500
    },
    step: {
        type: Number,
        default: 10
    },
    symbol: {
        type: String,
        default: ''
    }
});

const vModel = defineModel({ default: 0 });

</script>
