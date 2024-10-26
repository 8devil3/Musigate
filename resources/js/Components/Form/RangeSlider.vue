<template>
   <div class="has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-70 flex flex-col gap-3 text-left">
        <label v-if="props.label" :for="props.id" class="w-full mb-1 text-xs font-medium text-left text-white truncate disable:text-slate-400">
            {{ props.label }}
        </label>

        <input type="range"
            @change="emit('change')"
            v-model="vModel"
            :id="props.id ?? id"
            :min="props.min"
            :max="props.max"
            :step="props.step"
            :disabled="props.disabled"
            class="block w-full h-1.5 bg-slate-600 rounded-full appearance-none cursor-pointer disabled:cursor-not-allowed disabled:opacity-70"
        >

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Label from '@/Components/Form/Label.vue';
import FieldError from '@/Components/Form/FieldError.vue';

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
    unit: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: null
    },
    label: {
        type: String,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const vModel = defineModel({ default: 0 });

const emit = defineEmits(['change']);

const id = computed(()=>{
    return 'range-slider-' + Math.random() * 1000000000000;
});

</script>
