<template>
    <div class="inline-flex flex-col items-start text-left">
        <Label v-if="props.label" :label="props.label" :id="props.id ?? id" :required="props.required" />

        <select
            v-model="vModel"
            autocomplete="off"
            @change="emit('change')"
            :required="props.required"
            :disabled="props.disabled"
            :aria-label="props.label"
            :id="props.id ?? id"
            class="cursor-pointer"
            :class="[classes, vModel === '' ? 'text-slate-400' : 'text-slate-100' , props.error ? 'bg-slate-900 border-red-600 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600' : 'border-slate-400 focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500']"
        >
            <option value="" default selected :disabled="props.defaultOptionDisabled" :class="{'text-slate-400' : props.defaultOptionDisabled}">
                {{ props.default }}
            </option>

            <option v-if="props.isArray" v-for="option in props.options" :value="option">{{ option }}</option>
            <option v-else v-for="option, key in props.options" :value="parseInt(key)">{{ option }}</option>
        </select>

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Label from '@/Components/Form/Label.vue';
import FieldError from '@/Components/Form/FieldError.vue';

const props = defineProps({
    options: [Object, Array, Number],
    isArray: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    default: String,
    label: String,
    id: String,
    required: {
        type: Boolean,
        default: false
    },
    error: String,
    defaultOptionDisabled: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['change']);
const vModel = defineModel();

const classes = "text-left w-full h-8 pl-4 pr-6 py-0 text-sm bg-slate-800 border font-normal rounded-full placeholder:text-slate-300/80 disabled:bg-slate-800 placeholder:truncate truncate disabled:cursor-not-allowed disabled:text-slate-500 disabled:border-slate-500 font-sans placeholder:font-light";

const id = computed(()=>{
    return 'select-' + Math.random() * 1000000000000;
});

</script>

