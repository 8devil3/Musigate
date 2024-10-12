<template>
    <TransitionRoot as="template" :show="props.isOpen" >
        <Dialog @close="emit('close')" as="div" class="fixed inset-0 z-50 overflow-hidden">
            <TransitionChild
                as="template"
                enter="duration-300 ease-in"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 flex items-center justify-center min-h-full p-4 overflow-y-auto text-center">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-in"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel as="form" @submit.prevent="emit('submitted')" class="w-full max-w-lg p-6 space-y-6 overflow-hidden text-left border border-red-600 shadow-xl bg-slate-900 md:rounded-2xl">
                        <DialogTitle as="h3" class="flex items-start justify-between pb-4 text-lg text-red-500 border-b border-red-500">
                            <div class="flex items-center gap-2">
                                <i class="inline-flex items-center justify-center text-sm border-2 border-red-500 rounded-full fa-solid fa-exclamation size-6" />
                                <slot name="title" />
                            </div>

                            <button type="button" @click="emit('close')" title="Annulla e chiudi" class="pb-1 pl-2">
                                <i class="text-white fa-solid fa-xmark"></i>
                            </button>
                        </DialogTitle>

                        <DialogDescription v-if="$slots.description" as="div" class="text-sm font-extralight">
                            <slot name="description"/>
                        </DialogDescription>

                        <div v-if="$slots.actions" class="space-x-2">
                            <slot name="actions"/>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogTitle, DialogPanel, DialogDescription, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(['close', 'submitted']);

</script>
