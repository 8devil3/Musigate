<template>
    <TransitionRoot as="template" :show="props.isOpen" >
        <Dialog as="div" @close="emit('close')" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-80" />
            </TransitionChild>

            <div class="fixed inset-0 text-left md:p-4">
                <div class="flex items-center justify-center h-full">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel as="div" class="flex flex-col w-full h-full p-6 space-y-6 overflow-hidden bg-gray-900 md:h-auto md:border md:border-gray-600 md:rounded-2xl md:max-h-full" :class="[props.maxWidth, props.maxHeight]">
                            <DialogTitle as="h3" class="flex items-start justify-between gap-4 pb-2 m-0 text-xl border-b border-orange-500">
                                <slot name="title" />

                                <button type="button" @click="emit('close')" title="Chiudi" class="text-2xl">
                                    <i class="text-white fa-solid fa-xmark"></i>
                                </button>
                            </DialogTitle>

                            <DialogDescription v-if="$slots.description" as="div" class="h-full overflow-y-auto text-sm noscrollbar">
                                <slot name="description"/>
                            </DialogDescription>

                            <div v-if="$slots.actions" class="flex items-center gap-4">
                                <slot name="actions"/>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogTitle, DialogPanel, DialogDescription, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    isOpen: Boolean,
    maxWidth: {
        type: String,
        default: 'max-w-xl'
    },
    maxHeight: {
        type: String,
        default: 'max-h-full'
    }
});

const emit = defineEmits(['close']);

</script>
