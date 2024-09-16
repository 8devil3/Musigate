<template>
    <div v-if="props.progress" class="space-y-1">
        <div v-if="props.showPercentage" class="text-xs text-center">{{ progress }}%</div>
        <div class="h-1.5 border border-slate-400 rounded-full shadow-inner overflow-clip">
            <div :style="'width: ' + progress + '%'" class="h-full" :class="props.color" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    progress: Number,
    min: {
        type: [Number, String],
        default: 0,
    },
    max: {
        type: [Number, String],
        default: 100,
    },
    showPercentage: {
        type: Boolean,
        default: true
    },
    color: {
        type: String,
        default: 'bg-green-500'
    }
});

const progress = computed(()=>{
    return Math.round((parseInt(props.progress) * 100) / parseInt(props.max));
});

</script>
