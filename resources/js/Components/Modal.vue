<template>
    <teleport to="#modal">
        <Transition name="fade">
            <div v-if="props.isOpen" class="fixed inset-0 z-50 md:inset-4 overflow-clip">
                <div @click.stop="emit('close')" class="fixed inset-0 bg-black/70 backdrop-blur-md overflow-clip" />

                <component :is="props.as" class="absolute flex flex-col w-full h-full p-4 space-y-4 overflow-hidden -translate-x-1/2 -translate-y-1/2 md:p-6 md:space-y-6 top-1/2 left-1/2 bg-slate-900 md:border md:border-slate-600 md:rounded-2xl" :class="[props.maxWidth, props.maxHeight]">
                    <h3 class="flex items-start justify-between gap-4 pb-2 m-0 text-xl border-b border-orange-500">
                        <slot name="title" />

                        <button type="button" @click="emit('close')" title="Chiudi" class="text-2xl">
                            <i class="text-white fa-solid fa-xmark"></i>
                        </button>
                    </h3>

                    <div v-if="$slots.description" class="h-full overflow-y-auto text-sm noscrollbar">
                        <slot name="description"/>
                    </div>

                    <div v-if="$slots.actions">
                        <slot name="actions"/>
                    </div>
                </component>
            </div>
        </Transition>
    </teleport>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
    as: {
        type: String,
        default: 'div'
    },
    maxWidth: {
        type: String,
        default: 'max-w-xl'
    },
    maxHeight: {
        type: String,
        default: 'max-h-full'
    }
});

const emit = defineEmits(['close', 'submitted']);

</script>
