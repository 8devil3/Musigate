<template>
    <teleport to="#flash-message">
        <Transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="success" class="fixed z-50 -translate-x-1/2 top-8 lg:top-4 left-1/2">
                <div class="flex items-center gap-2 px-3 py-2 text-xs font-medium leading-none text-white border-2 rounded-full shadow-lg md:gap-3 md:text-sm whitespace-nowrap bg-emerald-900 border-emerald-500">
                    <i class="fa-solid fa-check" />
                    {{ usePage().props.flash.success }}
                </div>
            </div>
        </Transition>

        <div v-show="usePage().props.flash.error" class="fixed z-50 -translate-x-1/2 top-8 lg:top-4 left-1/2">
            <div class="flex items-center gap-2 px-3 py-2 text-xs font-medium leading-none text-white bg-red-900 border-2 border-red-500 rounded-full shadow-lg md:gap-3 md:text-sm whitespace-nowrap">
                <i class="fa-solid fa-circle-exclamation" />
                Salvataggio non riuscito!
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { ref, onBeforeUpdate, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const success = ref(false);

const dissolveFlashSuccess = ()=>{
    success.value = usePage().props.flash.success ? true : false;

    let timeoutId = setTimeout(() => {
        success.value = false;
    }, 2000000);
};

onBeforeUpdate(()=>{
    dissolveFlashSuccess();
});

</script>
