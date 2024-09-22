<template>
    <span class="relative mx-2">
        <button type="button" @click="isShowMsg = !isShowMsg" @mouseenter="showMsg(true)" @mouseleave="showMsg(false)" class="inline-flex items-center justify-center w-6 h-6 text-sm leading-none text-white border border-orange-500 rounded-full font-lemon bg-slate-800">?</button>
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

const showMsg = (e)=>{
    if(e) isShowMsg.value = true;
    else isShowMsg.value = false;
};


</script>
