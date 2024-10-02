<template>
    <div class="flex items-start gap-2">
        <i class="-mt-1 text-lg" :class="[props.icon, colorClasses]" />
        <div class="space-y-1">
            <div class="text-sm font-normal text-left" :class="colorClasses">
                {{ props.title }}
            </div>
            <div class="text-xs font-light text-slate-400">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    icon: {
        type: String,
        default: 'fa-solid fa-circle-info'
    },
    title: String,
    color: {
        type: String,
        default: 'info',
    },
});

const colorClasses = computed(()=>{
    switch (props.color) {
        case 'info':
        return 'text-blue-500';

        case 'warning':
        return 'text-amber-500';

        case 'danger':
        return 'text-red-500';

        case 'success':
        return 'text-green-500';
    }
})

</script>
