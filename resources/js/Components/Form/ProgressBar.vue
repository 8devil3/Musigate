<template>
    <div v-if="props.progress" class="w-full space-y-1">
        <div class="text-xs text-center">{{ props.progress.percentage }}%</div>
        <div class="w-full h-1.5 border rounded-full shadow-inner overflow-clip">
            <div :style="'width: ' + props.progress.percentage + '%'" class="h-full bg-emerald-600"></div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    progress: Object
});

</script>
