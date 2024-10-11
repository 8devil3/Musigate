<template>
    <SwitchGroup as="span" class="inline-flex items-start gap-3 p-1" :class="[{'opacity-40' : props.disabled}, {'cursor-not-allowed' : props.disabled}]">
        <Switch
            v-model="vModel"
            :disabled="props.disabled"
            :class="[vModel ? 'bg-orange-600' : 'bg-gray-400', {'cursor-not-allowed' : props.disabled}]"
            class="relative inline-flex w-8 h-4 mt-0.5 transition-colors duration-200 ease-in-out rounded-full shadow-inner cursor-pointer shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 disabled:cursor-not-allowed"
        >
            <span
                aria-hidden="true"
                :class="vModel ? 'translate-x-[14px]' : '-translate-x-1'"
                class="absolute block size-5 transition duration-200 ease-in-out transform -translate-y-1/2 bg-white rounded-full pointer-events-none top-1/2 ring-0 disabled:cursor-not-allowed"
            />
        </Switch>

        <div v-if="props.label || props.description" class="flex flex-col">
            <SwitchLabel v-if="props.label" class="text-sm leading-tight text-white cursor-pointer disabled:cursor-not-allowed" :class="{'cursor-not-allowed' : props.disabled}">
                {{ props.label }}
            </SwitchLabel>
            <SwitchDescription v-if="props.description" class="text-xs leading-tight text-gray-300 cursor-pointer disabled:cursor-not-allowed" :class="{'cursor-not-allowed' : props.disabled}">
                {{ props.description }}
            </SwitchDescription>
        </div>
    </SwitchGroup>
</template>

<script setup>
import { Switch, SwitchLabel, SwitchDescription, SwitchGroup } from '@headlessui/vue'

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    label: String,
    description: String
});

const vModel = defineModel({ default: false });

</script>
