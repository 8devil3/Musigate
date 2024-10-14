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
            :class="[classes, props.error ? 'border-red-500 bg-red-600/10 focus:ring-red-600/50 focus:border-red-600 focus:shadow-md focus:shadow-red-600' : 'focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500 bg-slate-900 border-slate-400']"
        >
            {{ vModel }}
        </textarea>
        
        <div class="flex justify-end gap-6">
            <FieldError :error="props.error" class="grow"/>
    
            <div v-if="props.minlength" class="mt-2 text-xs font-normal text-right" :class="vModel?.length < props.minlength ? 'text-red-500' : 'text-slate-300'">
                {{ vModel?.length ?? 0 }}/{{ props.minlength }} caratteri
            </div>
    
            <div v-if="props.maxlength" class="mt-2 text-xs font-normal text-right" :class="vModel?.length > props.maxlength ? 'text-red-500' : 'text-slate-300'">
                {{ vModel?.length ?? 0 }}/{{ props.maxlength }} caratteri
            </div>
        </div>
    </div>
</template>

<script setup>
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

const classes = "text-left form-textarea w-full px-3 py-2 text-sm font-light leading-normal text-white rounded-xl border placeholder:text-slate-500 disabled:bg-slate-800 disabled:cursor-not-allowed placeholder:truncate disabled:text-slate-600 disabled:border-slate-500 font-sans";

const vModel = defineModel({ default: null });

</script>

