<template>
    {{ setBodyScroll }}
    <teleport to="#modal">
        <Transition name="fade">
            <div v-if="props.isOpen" role="dialog" class="fixed inset-0 z-50 md:inset-4 overflow-clip">
                <div @click.stop="emit('close')" class="fixed inset-0 bg-black/70 backdrop-blur-md overflow-clip" />

                <form @submit.prevent="emit('submitted')" class="absolute flex flex-col w-full h-full max-w-lg p-4 space-y-4 overflow-hidden -translate-x-1/2 -translate-y-1/2 md:h-auto md:p-6 md:space-y-6 top-1/2 left-1/2 bg-slate-950 md:border md:border-red-600 md:rounded-2xl">
                    <h3 class="flex items-start justify-between pb-3 text-base text-red-500 border-b border-b-red-500 md:text-lg">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation" />
                            <slot name="title" />
                        </div>
    
                        <button type="button" @click.stop="emit('close')" title="Annulla e chiudi" class="pb-1 pl-2">
                            <i class="text-white fa-solid fa-xmark"></i>
                        </button>
                    </h3>
    
                    <p v-if="$slots.description" class="text-sm font-extralight">
                        <slot name="description"/>
                    </p>
    
                    <div v-if="$slots.actions" class="flex *:grow md:*:grow-0 gap-2">
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
