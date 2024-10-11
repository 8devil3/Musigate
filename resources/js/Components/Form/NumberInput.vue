<template>
    <div class="p-0 m-0 text-left max-w-40">
        <Label v-if="props.label" :label="props.label" :for="props.id ?? id" />

        <div class="flex bg-slate-800/50 h-8 border text-sm border-slate-400 rounded-full has-[input:disabled]:cursor-not-allowed has-[input:disabled]:border-slate-700 transition-colorsfocus-within:ring-orange-500/50 focus-within:border-orange-500 focus-within:shadow-md focus-within:shadow-orange-500" :class="{'text-red-500 bg-red-600/10 border-red-600' : props.error}">
            <button ref="downButton" type="button" @click="down()" :disabled="props.disabled || props.min >= vModel" class="flex items-center justify-center w-8 h-8 text-base leading-none text-orange-500 shrink-0 focus:ring-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-minus" />
            </button>

            <input
                type="number"
                @change="emit('change')"
                @input="emit('input')"
                v-model.number="vModel"
                :id="props.id ?? id"
                :min="props.min"
                :max="props.max"
                :step="props.step"
                :unit="props.unit"
                :required="props.required"
                :error="props.error"
                :disabled="props.disabled"
                class="p-0 m-0 text-sm font-light text-center text-white bg-transparent border-0 grow min-w-8 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50"
            />

            <div v-if="props.unit" class="self-center text-xs font-normal shrink-0 text-slate-400">
                {{ props.unit }}
            </div>
    
            <button ref="upButton" type="button" @click="up()" :disabled="props.disabled || props.max <= vModel" class="flex items-center justify-center w-8 h-8 text-base leading-none text-orange-500 shrink-0 focus:ring-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-plus" />
            </button>
        </div>

        <div v-show="props.error" class="px-1 mt-1 text-sm text-left text-red-500">{{ error }}</div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import Label from '@/Components/Form/Label.vue';

const props = defineProps({
    label: String,
    min: {
        type: Number,
        default: 0
    },
    max: {
        type: Number,
        default: 100000000
    },
    step: {
        type: Number,
        default: 1
    },
    disabled: Boolean,
    unit: String,
    required: Boolean,
    error: String,
});

const emit = defineEmits(['change', 'input']);

const vModel = defineModel({default: 0});

const upButton = ref(null);
const downButton = ref(null);

const up = ()=>{
    if(props.max <= vModel.value) return

    let number = Math.floor(vModel.value / props.step) * props.step;

    number += props.step;

    vModel.value = number;

    emit('change');
}

const down = ()=>{
    if(props.min >= vModel.value) return

    let number = Math.floor(vModel.value / props.step) * props.step;

    number -= props.step;

    vModel.value = number;

    emit('change');
}

const id = computed(()=>{
    return 'number-input-' + Math.random() * 1000000000000;
});

</script>
