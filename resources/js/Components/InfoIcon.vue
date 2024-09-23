<template>
    <span class="relative mx-1.5">
        <button type="button" @click="isShowMsg = !isShowMsg" @mouseenter="isShowMsg = true" @mouseleave="isShowMsg = false" class="inline-flex items-center justify-center w-5 h-5 text-xs leading-none text-white border border-orange-500 rounded-full font-lemon bg-slate-800">?</button>
        <span v-show="isShowMsg" class="absolute p-1 mb-1 text-xs text-white -translate-x-1/2 border border-orange-500 rounded bottom-full left-1/2 bg-slate-800">
            {{ props.msg }}
        </span>
    </span>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    msg: String,
});

const isShowMsg = ref(false);

</script>
