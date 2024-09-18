<template>
    <div class="inline-flex flex-col">
        <Label v-if="props.label" :label="props.label" :for="props.id ?? id" />

        <div class="flex items-center gap-1 bg-slate-800/50 border border-slate-400 rounded-full has-[:disabled]:cursor-not-allowed has-[:disabled]:border-slate-600 transition-colors has-[:disabled]:bg-slate-950 focus-within:ring-orange-500/50 focus-within:border-orange-500 focus-within:shadow-md focus-within:shadow-orange-500" :class="{'text-red-500 bg-red-600/10 border-red-600' : props.error}">
            <button type="button" @click="down()" class="flex items-center justify-center w-8 h-8 text-orange-500 shrink-0 focus:ring-0">
                <i class="text-sm fa-solid fa-minus" />
            </button>

            <input
                type="number"
                :id="props.id ?? id"
                v-model="vModel"
                :min="props.min"
                :max="props.max"
                :step="props.step"
                :unit="props.unit"
                :required="props.required"
                :error="props.error"
                class="w-full h-8 p-0 m-0 text-sm text-center text-white bg-transparent border-0 focus:outline-none focus:ring-0"
            />
    
            <button type="button" @click="up()" class="flex items-center justify-center w-8 h-8 text-orange-500 shrink-0 focus:ring-0">
                <i class="text-sm fa-solid fa-plus" />
            </button>
        </div>

        <div v-show="props.error" class="px-1 mt-1 text-sm text-left text-red-500">{{ error }}</div>
    </div>
</template>

<script setup>
import Label from '@/Components/Form/Label.vue';
import { computed } from 'vue';

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
    unit: String,
    required: Boolean,
    error: String,
});

const vModel = defineModel({default: 0});

const up = ()=>{
    if(props.max <= vModel.value) return

    let number = Math.floor(vModel.value / props.step) * props.step;

    number += props.step;

    vModel.value = number;
}

const down = ()=>{
    if(props.min >= vModel.value) return

    let number = Math.floor(vModel.value / props.step) * props.step;

    number -= props.step;

    vModel.value = number;
}

const id = computed(()=>{
    return 'numeric-input-' + Math.random() * 1000000000000;
});

</script>
