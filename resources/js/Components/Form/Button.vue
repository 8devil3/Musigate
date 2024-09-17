<template>
    <Link
        v-if="props.type === 'router'"
        :href="props.href"
        :class="[commonClasses, colorClasses]"
    >
        <i v-if="props.icon" :class="commonIconClasses"></i>
        <template v-if="props.text">
            {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
        </template>
    </Link>

    <a
        v-else-if="props.type === 'link'"
        :href="props.href"
        :class="[commonClasses, colorClasses]"
    >
        <i v-if="props.icon" :class="commonIconClasses"></i>
        <template v-if="props.text">
            {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
        </template>
    </a>

    <button
        v-else
        @click="emit('click')"
        :type="props.type"
        :disabled="props.disabled"
        :class="[commonClasses, colorClasses, {'px-8' : props.isLoading}] "
    >
        <template v-if="!props.isLoading">
            <i v-if="props.icon" :class="commonIconClasses"></i>
            <template v-if="props.text">
                {{ props.text.charAt(0).toUpperCase() + props.text.slice(1, props.text.length) }}
            </template>
        </template>
        
        <Spinner v-else class="w-5 h-5"/>
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
    outline: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['click']);

const commonClasses = "inline-flex items-center justify-center text-center text-white font-sans font-medium text-sm leading-none whitespace-nowrap h-8 rounded-full disabled:opacity-50 focus:ring-2 disabled:cursor-not-allowed shadow-md gap-2 px-4 border-2 py-0 capitalize focus:outline-0";

const commonIconClasses = `${props.icon} text-white text-sm leading-none`

const colorClasses = computed(() => {
    let classes = null;
    
    switch (props.color) {
        case 'green':
            classes = [props.outline ? 'bg-green-500/20' : 'bg-green-600', 'border-green-600 hover:bg-green-500 active:bg-green-500 focus:ring-green-500/50 transition-colors shadow-green-500/30'];
        break;
        case 'yellow':
            classes = [props.outline ? 'bg-yellow-500/20' : 'bg-yellow-600', 'border-yellow-600 hover:bg-yellow-500 active:bg-yellow-500 focus:ring-yellow-500/50 transition-colors shadow-yellow-500/30'];
        break;
        case 'gray':
            classes = [props.outline ? 'bg-gray-500/20' : 'bg-gray-600', 'border-gray-600 hover:bg-gray-500 active:bg-gray-500 focus:ring-gray-500/50 transition-colors shadow-gray-500/30'];
        break;
        case 'red':
            classes = [props.outline ? 'bg-red-500/20' : 'bg-red-600', 'border-red-600 hover:bg-red-500 active:bg-red-500 focus:ring-red-500/50 transition-colors shadow-red-500/30'];
        break;
        case 'orange':
            classes = [props.outline ? 'bg-orange-500/20' : 'bg-orange-600', 'border-orange-600 hover:bg-orange-500 active:bg-orange-500 focus:ring-orange-500/50 transition-colors shadow-orange-500/30'];
        break;
        case 'indigo':
            classes = [props.outline ? 'bg-indigo-500/20' : 'bg-indigo-600', 'border-indigo-600 hover:bg-indigo-500 active:bg-indigo-500 focus:ring-indigo-500/50 transition-colors shadow-indigo-500/30'];
        break;
        case 'violet':
            classes = [props.outline ? 'bg-violet-500/20' : 'bg-violet-600', 'border-violet-600 hover:bg-violet-500 active:bg-violet-500 focus:ring-violet-500/50 transition-colors shadow-violet-500/30'];
        break;

        case 'sky':
            classes = [props.outline ? 'bg-sky-500/20' : 'bg-sky-600', 'border-sky-600 hover:bg-sky-500 active:bg-sky-500 focus:ring-sky-500/50 transition-colors shadow-sky-500/30'];
        break;
    }

    return classes
});

</script>


