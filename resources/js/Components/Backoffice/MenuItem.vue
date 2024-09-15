<template>
    <Disclosure v-if="props.isSubmenu" v-slot="{ open }" defaultOpen>
        <DisclosureButton class="flex items-center justify-between w-full p-3 text-base text-gray-300 bg-transparent border-transparent border-x-4">
            <div class="flex items-center gap-2">
                <i class="w-5 text-center" :class="props.icon"></i>
                {{ props.text }}
            </div>
            <i class="text-orange-500 transition-transform fa-solid fa-chevron-down" :class="{'rotate-180' : open}"></i>
        </DisclosureButton>

        <DisclosurePanel as="ul" class="ml-6 space-y-1 border-l border-gray-600">
            <li v-for="subItem in props.subItems">
                <Link
                    :href="subItem.href"
                    class="flex items-center gap-1 p-2 text-xs text-gray-300 transition-colors border-transparent border-x-4 hover:border-l-orange-500 hover:bg-gray-700/50"
                    :class="[{'border-l-orange-500 hover:bg-transparent text-zinc-100 bg-gray-700/80' : subItem.active}]"
                >
                    <i class="w-5 text-center" :class="subItem.icon"></i>
                    {{ subItem.text }}
                </Link>
            </li>
        </DisclosurePanel>
    </Disclosure>

    <li v-else>
        <Link
            
            :href="props.href"
            class="flex items-center gap-2 px-3 py-3 text-base text-gray-300 transition-colors border-transparent border-x-4 hover:border-l-orange-500 hover:bg-gray-700/50"
            :class="[{'border-l-orange-500 hover:bg-transparent text-zinc-100 bg-gray-700/80' : props.active}]"
        >
            <i class="w-5 text-center" :class="props.icon"></i>
            {{ props.text }}
        </Link>
    </li>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';

const props = defineProps({
    href: String,
    text: String,
    active: {
        type: Boolean,
        default: false
    },
    icon: {
        type: String,
        default: null
    },
    isSubmenu: {
        type: Boolean,
        default: false
    },
    subItems: {
        type: Array,
        default: []
    }
});

</script>
