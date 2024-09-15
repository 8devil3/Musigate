<template>
    <div class="flex flex-col items-start text-left">
        <Label v-if="props.label" :label="props.label" :id="props.id" :required="props.required" />

        <select
            :name="props.id"
            v-model="vModel"
            autocomplete="off"
            :required="props.required"
            :disabled="props.disabled"
            :value="props.modelValue"
            :aria-label="props.label"
            :id="props.id"
            class="cursor-pointer"
            :class="[commonClasses.select, {'text-gray-400' : vModel === null}, props.error && 'border-red-600 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600']"
        >
            <option :value="null" default selected :disabled="props.defaultOptionDisabled" :class="{'text-gray-400' : props.defaultOptionDisabled}">
                {{ props.default }}
            </option>
            
            <option v-if="props.isArray" v-for="option in props.options" :value="option">{{ option }}</option>
            <option v-else v-for="option, key in props.options" :value="parseInt(key)">{{ option }}</option>
        </select>

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import Label from '@/Components/Form/Label.vue';
import FieldError from '@/Components/Form/FieldError.vue';
import commonClasses from '@/Components/Form/commonClasses.json';

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
    required: Boolean,
    error: String,
    defaultOptionDisabled: {
        type: Boolean,
        default: true
    }
});

const vModel = defineModel({ default: null });

</script>

