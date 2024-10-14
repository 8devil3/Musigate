<template>
    <teleport to="#flash-message">
        <div class="fixed z-50 -translate-x-1/2 top-8 lg:top-4 left-1/2">
            <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-show="success" class="flex items-center gap-2 px-4 py-2 text-sm font-medium leading-none text-white border-2 rounded-full shadow-lg bg-emerald-900 border-emerald-500">
                    <i class="fa-solid fa-check" />
                    {{ usePage().props.flash.success }}
                </div>
            </transition>
    
            <div v-show="error" class="flex items-center gap-2 p-2 text-sm font-medium leading-none text-white bg-red-600 border-2 border-red-900 rounded-full shadow-lg">
                <i class="fa-solid fa-circle-exclamation" />
                Salvataggio non riuscito!
            </div>
        </div>
    </teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const success = ref(usePage().props.flash.success ? true : false);
const error = ref(usePage().props.flash.error ? true : false);

const dissolveFlashSuccess = ()=>{
    let timoutId1 = setTimeout(() => {
        success.value = false;

        let timoutId2 = setTimeout(() => {
            usePage().props.flash.success = null;
            clearTimeout(timoutId2);
        }, 2000);

        clearTimeout(timoutId1);
    }, 2000);
};

onMounted(()=>{
    dissolveFlashSuccess();
});

</script>
