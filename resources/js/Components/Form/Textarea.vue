<template>
    <div class="flex flex-col text-left rounded-lg">
        <Label v-if="props.label" :label="props.label" :id="props.id" :required="props.required" />

        <textarea
            v-model.trim="vModel"
            :id="props.id"
            :placeholder="props.placeholder"
            :required="props.required"
            :autofocus="props.autofocus"
            :rows="props.rows"
            :minlength="props.minlength"
            :maxlength="props.maxlength"
            :aria-label="props.label"
            :aria-placeholder="props.placeholder"
            :class="[commonClasses.textarea, props.error && 'border-red-600 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600']"
        >
            {{ vModel }}
        </textarea>
        
        <FieldError :error="props.error"/>

        <div v-if="props.minlength" class="mt-2 text-xs text-right" :class="vModel?.length < props.minlength ? 'text-red-500' : 'text-gray-300'">
            {{ vModel?.length ?? 0 }}/{{ props.minlength }} caratteri
        </div>

        <div v-if="props.maxlength" class="mt-2 text-xs text-right" :class="vModel?.length > props.maxlength ? 'text-red-500' : 'text-gray-300'">
            {{ vModel?.length ?? 0 }}/{{ props.maxlength }} caratteri
        </div>
    </div>
</template>

<script setup>
import Label from '@/Components/Form/Label.vue';
import FieldError from '@/Components/Form/FieldError.vue';
import commonClasses from '@/Components/Form/commonClasses.json';

const props = defineProps({
    label: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: null,
    },
    id: {
        type: String,
        default: null
    },
    autofocus: {
        type: Boolean,
        default: false
    },
    required: {
        type: Boolean,
        default: false
    },
    error: {
        type: String,
        default: null
    },
    rows: {
        type: Number,
        default: 16
    },
    minlength: {
        type: Number,
        default: null
    },
    maxlength: {
        type: Number,
        default: null
    },
});

const vModel = defineModel({ default: null });

</script>

