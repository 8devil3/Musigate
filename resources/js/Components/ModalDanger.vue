<template>
    {{ setBodyScroll }}
    <teleport to="#modal">
        <Transition name="fade">
            <div v-if="props.isOpen" role="dialog" class="fixed z-40 inset-4 overflow-clip">
                <div @click.stop="emit('close')" class="fixed inset-0 bg-black/70 backdrop-blur-md" />

                <form @submit.prevent="emit('submitted')" class="absolute w-full max-w-lg p-4 space-y-4 overflow-hidden text-left -translate-x-1/2 -translate-y-1/2 border border-red-600 shadow-xl top-1/2 left-1/2 md:p-6 md:space-y-6 bg-slate-950 rounded-xl md:rounded-2xl">
                    <h3 class="flex items-start justify-between pb-3 text-base text-red-500 border-b border-red-500 md:text-lg">
                        <div class="flex items-center gap-2">
                            <i class="inline-flex items-center justify-center text-xs border-2 border-red-500 rounded-full md:text-sm fa-solid fa-exclamation size-5 md:size-6" />
                            <slot name="title" />
                        </div>
    
                        <button type="button" @click.stop="emit('close')" title="Annulla e chiudi" class="pb-1 pl-2">
                            <i class="text-white fa-solid fa-xmark"></i>
                        </button>
                    </h3>
    
                    <p v-if="$slots.description" class="text-sm font-extralight">
                        <slot name="description"/>
                    </p>
    
                    <div v-if="$slots.actions" class="space-x-2">
                        <slot name="actions"/>
                    </div>
                </form>
            </div>
        </Transition>
    </teleport>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    isOpen: Boolean,
});

const emit = defineEmits(['close', 'submitted']);

const setBodyScroll = computed(()=>{
    if(props.isOpen){
        document.body.classList.add('overflow-hidden');
    } else {
        document.body.classList.remove('overflow-hidden');
    }
});

</script>
