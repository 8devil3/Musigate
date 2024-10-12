<template>
    <div class="inline-flex items-start gap-2 p-2 border-2 rounded-xl" :class="colorClasses.border">
        <i class="flex items-center justify-center text-xs border-2 rounded-full shrink-0 size-5" :class="[props.icon, colorClasses.text, colorClasses.border]" />
        <div class="space-y-1">
            <div class="text-sm font-medium text-left border-0" :class="colorClasses.text">
                {{ props.title }}
            </div>
            <div class="text-xs font-light text-slate-300">
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
        default: 'fa-solid fa-info'
    },
    title: String,
    color: {
        type: String,
        default: 'danger',
    },
});

const colorClasses = computed(()=>{
    let color = {
        border: null,
        text: null,
    };

    switch (props.color) {
        case 'info':
            color.border = 'border-blue-500';
            color.text = 'text-blue-500';
        break;

        case 'warning':
            color.border = 'border-amber-500';
            color.text = 'text-amber-500';
        break;

        case 'danger':
            color.border = 'border-red-500';
            color.text = 'text-red-500';
        break;

        case 'success':
            color.border = 'border-green-500';
            color.text = 'text-green-500';
        break;
    }

    return color;
})

</script>
