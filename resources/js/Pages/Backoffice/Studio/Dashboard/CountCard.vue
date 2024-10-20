<template>
    <div class="p-4 border-2 border-orange-600 overflow-clip bg-slate-900 rounded-2xl">
        <h3 class="flex items-start w-full gap-3 text-lg leading-tight">
            <i class="mt-0.5" :class="[props.icon, props.iconColor]" />
            <div class="truncate">
                {{ props.title }}
            </div>
        </h3>

        <div v-if="props.count !== null" class="mt-4 text-4xl leading-tight font-lemon">
            {{ props.count }}
        </div>

        <div v-if="$slots.content" class="mt-4">
            <slot name="content" />
        </div>

        <Link v-if="props.routeName" :href="route(props.routeName)" class="block mt-1 font-medium text-orange-500 transition-colors hover:text-orange-400">
            {{ props.routeLabel }}
            <i class="ml-1 fa-solid fa-arrow-right" />
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    icon: String,
    iconColor: {
        type: String,
        default: 'text-white'
    },
    routeName: {
        type: String,
        default: null,
    },
    routeParams: {
        type: [Array, String, Number],
        default: null,
    },
    routeLabel: {
        type: String,
        default: null,
    },
    count: {
        type: Number,
        default: null,
    },
});

</script>
