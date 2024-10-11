<template>
    <Link
        v-if="props.type === 'router'"
        :href="props.href"
        :class="[commonClasses, buttonColor]"
    >
        <Spinner v-if="props.isLoading" class="size-4" />
        <i class="text-sm" :class="props.icon"></i>
    </Link>

    <a
        v-else-if="props.type === 'link'"
        :href="props.href"
        :class="[commonClasses, buttonColor]"
    >
        <Spinner v-if="props.isLoading" class="size-4" />
        <i class="text-sm" :class="props.icon"></i>
    </a>

    <button
        v-else
        @click="emit('click')"
        :type="props.type"
        :class="[commonClasses, buttonColor]"
        :disabled="props.isLoading"
    >
        <Spinner v-if="props.isLoading" class="size-4" />
        <i class="text-sm" v-else :class="props.icon"></i>
    </button>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    icon: String,
    color: {
        type: String,
        default: 'orange'
    },
    type: {
        type: String,
        default: 'button'
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['click']);

const commonClasses = 'inline-flex items-center justify-center size-8 text-[16px] transition-colors rounded-full cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed shrink-0'

const buttonColor = computed(()=>{
    let bc = '';

    switch (props.color) {
        case 'green':
            bc = 'text-green-500 bg-green-500/20 hover:bg-green-500/30';
        break;

        case 'yellow':
            bc = 'text-yellow-500 bg-yellow-500/20 hover:bg-yellow-500/30';
        break;

        case 'gray':
            bc = 'text-gray-400 bg-gray-400/20 hover:bg-gray-400/30';
        break;

        case 'red':
            bc = 'text-red-500 bg-red-500/20 hover:bg-red-500/30';
        break;

        case 'orange':
            bc = 'text-orange-500 bg-orange-500/20 hover:bg-orange-500/30';
        break;

        case 'indigo':
            bc = 'text-indigo-500 bg-indigo-500/20 hover:bg-indigo-500/30';
        break;

        case 'violet':
            bc = 'text-violet-500 bg-violet-500/20 hover:bg-violet-500/30';
        break;
    }
    
    return bc;
});

</script>
