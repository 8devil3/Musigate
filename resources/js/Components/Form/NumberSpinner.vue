<template>
    <div>
        <div class="flex items-center gap-1 p-1.5 leading-none text-center bg-gray-800/80 border border-gray-400 rounded-full focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500">
            <button type="button" @click="decrease()" class="shrink-0">
                <i class="w-6 h-4 text-base leading-none text-orange-500 fa-solid fa-minus"></i>
            </button>
    
            <input
                type="number"
                :value="vModel"
                @input="emit('update:modelValue', $event.target.value)"
                :id="props.id"
                :min="props.min"
                :max="props.max"
                :step="props.step"
                :required="props.required"
                :disabled="props.disabled"
                autocomplete="off"
                class="w-12 p-0 text-base font-light leading-none text-center text-white bg-transparent border-0 disabled:text-gray-400"
            />
            
            <button type="button" @click="increase()" class="shrink-0">
                <i class="w-6 h-4 text-base leading-none text-orange-500 fa-solid fa-plus"></i>
            </button>
        </div>
    
        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import FieldError from '@/Components/Form/FieldError.vue';

const props = defineProps({
    id: {
        type: String,
        default: null
    },
    min: {
        type: [Number, String],
        default: 1
    },
    max: {
        type: [Number, String],
        default: 10000
    },
    step: {
        type: Number,
        default: 1
    },
    error: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const vModel = defineModel({ default: 0 });

const emit = defineEmits(['update:model-value']);

const increase = ()=>{
    vModel.value++
}

const decrease = ()=>{
    vModel.value--
}

</script>
