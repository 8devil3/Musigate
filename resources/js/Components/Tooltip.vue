<template>
    <div>
        <div class="relative">
            <button type="button" @mouseover="data.isOpen = true" @mouseout="data.isOpen = false" @click="data.isOpen = !data.isOpen" class="flex items-center justify-center w-4 h-4 p-1 text-sm font-bold text-center text-black border-2 rounded-full border-emerald-600 bg-gray-50">
                i
            </button>
        
            <div v-show="data.isOpen" class="absolute top-0 z-50 origin-bottom -translate-x-1/2 -translate-y-full left-1/2 w-[240px]">
                <div class="w-full p-1.5 text-xs text-white rounded-md shadow-md bg-emerald-600">
                    {{ props.text }}
                </div>
                <svg height="12" viewBox="0 0 6 6" fill="none" class="mx-auto" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 0H0L3 4L6 0Z" fill="#00935F"/>
                </svg>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';

const props = defineProps({
    text: String
});

const data = reactive({
    isOpen: false
});

</script>
