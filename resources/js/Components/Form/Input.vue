<template>
    <div class="p-0 m-0 text-left">
        <Label v-if="props.label" :label="props.label" :id="props.id ?? id" :required="props.required" />

        <div class="relative w-full">
            <input
                v-model.trim="vModel"
                @change="emit('change')"
                @input="emit('input')"
                :type="props.type"
                :id="props.id ?? id"
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
                :class="[classes, props.unit && 'pr-10', props.error && 'border-red-600 bg-red-600/10 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600']"
            >

            <div v-if="props.unit" class="absolute inset-y-0 flex items-center text-xs text-right text-slate-400 right-4">
                {{ props.unit }}
            </div>
        </div>

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Label from '@/Components/Form/Label.vue';
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

const classes = "text-left w-full h-8 px-4 py-0 text-sm text-white bg-slate-800/50 border border-slate-400 rounded-full placeholder:text-slate-300/80 disabled:bg-slate-800 placeholder:truncate truncate disabled:cursor-not-allowed disabled:text-slate-500 disabled:border-slate-500 font-sans placeholder:font-light font-normal focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500";

const emit = defineEmits(['input', 'change']);

const vModel = defineModel({ default: null });

const id = computed(()=>{
    return 'input-' + Math.random() * 1000000000000;
});

const clearInput = ()=>{
    vModel.value = null;
    emit('input', null);
}

</script>
