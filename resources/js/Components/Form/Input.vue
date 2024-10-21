<template>
    <div class="p-0 m-0 text-left">
        <Label v-if="props.label" :label="props.label" :id="props.id ?? id" :required="props.required" />

        <div
            class="flex w-full h-8 px-3 py-0 font-sans border rounded-full overflow-clip has-[:disabled]:bg-slate-800 has-[:disabled]:cursor-not-allowed has-[:disabled]:text-slate-500 has-[:disabled]:border-slate-500"

            :class="props.error ? 'border-red-500 bg-red-600/10 focus-within:ring-red-600/50 focus-within:border-red-600 focus-within:shadow-md focus-within:shadow-red-600' : 'bg-slate-900 focus-within:ring-orange-500/50 focus-within:border-orange-500 focus-within:shadow-md border-slate-400 focus-within:shadow-orange-500'"
        >
            <div v-if="props.icon" class="flex items-center pr-2 text-xs text-orange-500" :class="[props.disabled && 'text-slate-500', props.error && 'text-red-500']">
                <i :class="props.icon" />
            </div>

            <input
                v-model.trim="vModel"
                @change="emit('change')"
                @input="emit('input')"
                @keypress="emit('keypress')"
                :type="props.type"
                :id="props.id ?? id"
                :placeholder="props.placeholder"
                :autocomplete="props.autocomplete"
                :min="props.min"
                :inputmode="props.inputmode"
                :max="props.max"
                :step="props.step"
                :pattern="props.pattern"
                :disabled="props.disabled"
                :required="props.required"
                :accept="props.accept"
                :autofocus="props.autofocus"
                :aria-label="props.label"
                :aria-placeholder="props.placeholder"
                ref="inputElement"
                class="w-full p-0 text-sm font-light truncate bg-transparent border-0 outline-none grow focus:ring-0 focus:outline-none placeholder:font-light disabled:text-slate-500 disabled:placeholder:text-slate-500 placeholder:truncate placeholder:text-slate-500 disabled:cursor-not-allowed"
                :class="props.error && 'text-red-500 placeholder:text-red-300'"
            >

            <button v-if="!props.disabled && props.clearable && vModel" @click="clear()" type="button" tabindex="-1" class="flex items-center justify-center w-4 pl-1 shrink-0">
                <i class="text-xs fa-solid fa-circle-xmark text-slate-400" />
            </button>

            <div v-if="props.unit" class="flex items-center pl-1 text-xs font-normal truncate" :class="[props.disabled && 'text-slate-500', props.error && 'text-red-400']">
                {{ props.unit }}
            </div>
        </div>

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
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
    icon: {
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
    clearable: {
        type: Boolean,
        default: true,
    },
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
    },
    inputmode: {
        type: String,
        default: null
    }
});

const emit = defineEmits(['change', 'input', 'keypress', 'clear']);
const inputElement = ref(null);
const vModel = defineModel({ default: null });

const id = computed(()=>{
    return 'input-' + Math.random() * 1000000000000;
});

const clear = ()=>{
    vModel.value = null;
    emit('clear');
    inputElement.value.focus();
};

</script>
