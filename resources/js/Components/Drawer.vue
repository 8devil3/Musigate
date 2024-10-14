<template>
    <teleport to="#drawer">
        <Transition name="drawer">
            <div v-if="props.isOpen" class="fixed inset-0 z-50 overflow-clip">
                <div @click.stop="emit('close')" class="fixed inset-0 bg-black/70 backdrop-blur-md overflow-clip" />
                
                <div class="absolute inset-y-0 right-0">
                    <button type="button" class="absolute p-4 leading-none text-white top-2 -left-11 shrink-0 focus:outline-none focus:ring-0" title="Chiudi" @click.stop="emit('close')">
                        <i class="fa-solid fa-xmark" />
                    </button>

                    <div class="h-full bg-slate-900">
                        <slot />
                    </div>
                </div>
            </div>
        </Transition>
    </teleport>
</template>

<script setup>
const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(['close']);

</script>
