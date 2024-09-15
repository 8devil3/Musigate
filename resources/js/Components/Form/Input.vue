<template>
    <div class="flex flex-col items-start text-left">
        <Label v-if="props.label" :label="props.label" :id="props.id" :required="props.required" />

        <div class="relative w-full">
            <input
                ref="input"
                v-model.trim="vModel"
                :type="props.type"
                :id="props.id"
                :placeholder="props.placeholder"
                :autocomplete="props.autocomplete"
                :min="props.min"
                :max="props.max"
                :step="props.step"
                :pattern="props.pattern"
                :disabled="props.disabled"
                :required="props.required"
                :accept="props.accept"
                :autofocus="props.autofocus"
                :aria-label="props.label"
                :aria-placeholder="props.placeholder"
                :class="[commonClasses.input, props.unit && 'pr-10', props.error && 'border-red-600 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600']"
            >

            <div class="absolute inset-y-0 flex items-center text-xs text-right text-gray-400 right-4">
                {{ props.unit }}
            </div>
        </div>

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Label from '@/Components/Form/Label.vue';
import commonClasses from '@/Components/Form/commonClasses.json';
import FieldError from '@/Components/Form/FieldError.vue';

const props = defineProps({
    label: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'text',
    },
    id: {
        type: String,
        default: null
    },
    accept: String,
    min: {
        type: [Number, String],
        default: null
    },
    max: {
        type: [Number, String],
        default: null
    },
    step: {
        type: Number,
        default: null
    },
    autocomplete: {
        type: String,
        default: 'off'
    },
    pattern: {
        type: String,
        default: null
    },
    autofocus: {
        type: Boolean,
        default: false
    },
    unit: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const vModel = defineModel({ default: null });

const input = ref(null);

onMounted(() => {
    if(props.autofocus) input.value.focus();
});

</script>
