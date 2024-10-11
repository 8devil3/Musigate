<template>
    <button type="button" @click="inputColor.click()" class="relative size-8 rounded-full shadow-inner shrink-0" :style="'background-color:' + vModel">
        <input
            type="color"
            ref="inputColor"
            v-model="vModel"
            :id="props.id"
            :aria-label="props.label"
            :disabled="props.disabled"
            :required="props.required"
            :autofocus="props.autofocus"
            class="absolute w-0 h-0 border-0 -z-10 bg-none"
        >
    </button>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    label: {
        type: String,
        default: null
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
    disabled: {
        type: Boolean,
        default: false
    },
});

const inputColor = ref(null);

const vModel = defineModel({ default: '#ffffff' });

</script>
