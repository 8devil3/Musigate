<template>
    <div class="p-2 space-y-1 border-2 rounded-xl" :class="classes.border">
        <div class="flex items-center">
            <i class="w-6 text-base shrink-0" :class="[classes.icon, classes.text]" />
            <div class="text-sm font-medium text-left border-0" :class="classes.text">
                {{ props.title }}
            </div>
        </div>

        <div class="ml-6 text-xs font-light text-slate-300">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: String,
    color: {
        type: String,
        default: 'danger',
    },
});

const classes = computed(()=>{
    let classes = {
        border: null,
        text: null,
        icon: null,
    };

    switch (props.color) {
        case 'info':
            classes.border = 'border-blue-800';
            classes.text = 'text-blue-500';
            classes.icon = 'fa-solid fa-circle-info';
        break;

        case 'warning':
            classes.border = 'border-amber-800';
            classes.text = 'text-amber-500';
            classes.icon = 'fa-solid fa-circle-exclamation';
        break;

        case 'danger':
            classes.border = 'border-red-800';
            classes.text = 'text-red-500';
            classes.icon = 'fa-solid fa-circle-exclamation';
        break;

        case 'success':
            classes.border = 'border-green-800';
            classes.text = 'text-green-500';
            classes.icon = 'fa-solid fa-circle-check';
        break;
    }

    return classes;
})

</script>
