<template>
    <Link
        v-if="props.type === 'router'"
        :href="props.href"
        :class="[commonClasses, colorClasses]"
    >
        <Spinner v-if="props.isLoading" class="size-5 white"/>
        <i v-if="props.icon && !iconRight && !props.isLoading" :class="commonIconClasses" />
        <template v-if="props.text">
            {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
        </template>
        <i v-if="props.icon && iconRight && !props.isLoading" :class="commonIconClasses" />
    </Link>

    <a
        v-else-if="props.type === 'link'"
        :href="props.href"
        :class="[commonClasses, colorClasses]"
    >
        <Spinner v-if="props.isLoading" class="size-5 white"/>
        <i v-if="props.icon && !iconRight && !props.isLoading" :class="commonIconClasses" />
        <template v-if="props.text">
            {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
        </template>
        <i v-if="props.icon && iconRight && !props.isLoading" :class="commonIconClasses" />
    </a>

    <button
        v-else
        @click="emit('click')"
        :type="props.type"
        :disabled="props.disabled"
        :class="[commonClasses, colorClasses, {'px-8' : props.isLoading}] "
    >
        <Spinner v-if="props.isLoading" class="size-5 white"/>
        <i v-if="props.icon && !iconRight && !props.isLoading" :class="commonIconClasses" />
        <template v-if="props.text">
            {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
        </template>
        <i v-if="props.icon && iconRight && !props.isLoading" :class="commonIconClasses" />
    </button>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    text: {
        type: String,
        default: null,
    },
    href: {
        type: String,
        default: '#',
    },
    iconRight: {
        type: Boolean,
        default: false
    },
    color: {
        type: String,
        default: 'orange'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    icon: {
        type: String,
        default: null
    },
    isLoading: {
        type: Boolean,
        default: false
    },
});

const emit = defineEmits(['click']);

const commonClasses = "inline-flex items-center justify-center text-center text-white font-sans font-medium text-sm leading-none whitespace-nowrap h-8 rounded-full disabled:opacity-50 focus:ring-2 disabled:cursor-not-allowed shadow-md gap-2 transition-colors px-3 md:px-4 py-0 focus:outline-0";

const commonIconClasses = `${props.icon} text-white text-sm leading-none`

const colorClasses = computed(() => {
    let classes = null;
    
    switch (props.color) {
        case 'green':
            classes = 'bg-green-600 hover:bg-green-500 active:bg-green-500 focus:ring-green-500/50 shadow-green-500/30';
        break;
        case 'yellow':
            classes = 'bg-yellow-600 hover:bg-yellow-500 active:bg-yellow-500 focus:ring-yellow-500/50 shadow-yellow-500/30';
        break;
        case 'gray':
            classes = 'bg-slate-600 hover:bg-slate-500 active:bg-slate-500 focus:ring-slate-500/50 shadow-slate-500/30';
        break;
        case 'slate':
            classes = 'bg-slate-600 hover:bg-slate-500 active:bg-slate-500 focus:ring-slate-500/50 shadow-slate-500/30';
        break;
        case 'red':
            classes = 'bg-red-600 hover:bg-red-500 active:bg-red-500 focus:ring-red-500/50 shadow-red-500/30';
        break;
        case 'orange':
            classes = 'bg-orange-600 hover:bg-orange-500 active:bg-orange-500 focus:ring-orange-500/50 shadow-orange-500/30';
        break;
        case 'indigo':
            classes = 'bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-500 focus:ring-indigo-500/50 shadow-indigo-500/30';
        break;
        case 'violet':
            classes = 'bg-violet-600 hover:bg-violet-500 active:bg-violet-500 focus:ring-violet-500/50 shadow-violet-500/30';
        break;
        case 'sky':
            classes = 'bg-sky-600 hover:bg-sky-500 active:bg-sky-500 focus:ring-sky-500/50 shadow-sky-500/30';
        break;
        case 'green':
            classes = 'bg-green-600 hover:bg-green-500 active:bg-green-500';
        break;
        case 'telegram':
            classes = 'bg-telegram hover:brightness-105 active:bg-telegram';
        break;
        case 'whatsapp':
            classes = 'bg-whatsapp hover:brightness-105 active:bg-whatsapp';
        break;
        case 'messenger':
            classes = 'bg-messenger hover:brightness-105 active:bg-messenger';
        break;
    }

    return classes
});

</script>


