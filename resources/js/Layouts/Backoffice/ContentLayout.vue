<template>
    <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="h-full overflow-clip">
        <fieldset :disabled="props.isLoading" class="h-full overflow-y-auto">
            <h1 v-if="props.title" class="sticky top-0 z-10 flex items-center justify-between w-full h-12 gap-6 px-4 m-0 text-base border-b lg:h-16 lg:px-6 bg-slate-900/80 lg:bg-slate-950/50 backdrop-blur-sm md:text-xl border-slate-800">
                <div class="flex items-center w-full gap-2 leading-tight md:gap-3">
                    <Link v-if="props.backRoute" :href="props.backRoute">
                        <i class="mr-1 text-base text-orange-500 lg:text-lg fa-solid fa-chevron-left"></i>
                    </Link>

                    <i v-if="props.icon" :class="props.icon"></i>

                    <div class="truncate grow">
                        {{ props.title }}
                    </div>
                </div>
                
                <div v-if="$slots.actions" class="hidden gap-2 lg:flex">
                    <slot name="actions"/>
                </div>

                <button type="button" @click="isOpenDrawer = true" class="lg:hidden">
                    <i class="py-1 pr-1 text-lg fa-solid fa-bars" />
                </button>
            </h1>
    
            <div v-if="$slots.content" class="p-4 space-y-8 lg:p-6" :class="{'pb-20' : $slots.actions}">
                <Tabs v-if="props.tabLinks" :tabLinks="tabLinks"/>

                <p v-if="$slots.description" class="pb-6 text-sm border-b text-slate-300 border-slate-800">
                    <slot name="description"/>
                </p>

                <slot name="content"/>
            </div>
        </fieldset>
    </component>

    <!-- actions mobile -->
    <div v-if="$slots.actions" class="fixed flex justify-end gap-2 inset-x-4 bottom-4 lg:hidden">
        <slot name="actions"/>
    </div>
    <!-- / -->

    <!-- messaggi flash -->
    <div class="fixed -translate-x-1/2 top-9 lg:top-4 left-1/2 z-[2000]">
        <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="success" class="flex items-center gap-2 px-4 py-2 text-sm font-medium leading-none text-white border-2 rounded-full shadow-lg bg-emerald-600/20 border-emerald-500">
                <i class="fa-solid fa-check" />
                {{ usePage().props.flash.success }}
            </div>
        </transition>

        <div v-show="error" class="flex items-center gap-2 p-2 text-sm font-medium leading-none text-white border-2 border-red-600 rounded-full shadow-lg bg-red-600/20">
            <i class="fa-solid fa-circle-exclamation" />
            Salvataggio non riuscito!
        </div>
    </div>
    <!-- / -->


    <!-- drawer mobile -->
    <TransitionRoot as="template" :show="isOpenDrawer">
        <Dialog class="relative z-40" @close="isOpenDrawer = false">
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 transition-opacity bg-orange-500/50 backdrop-blur-md" />
            </TransitionChild>

            <div class="fixed inset-y-0 left-0 flex pr-10 pointer-events-none">
                <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="-translate-x-full">
                    <DialogPanel class="relative shadow-lg pointer-events-auto">
                        <button type="button" class="absolute p-4 leading-none text-white top-2 -right-11 bsolute shrink-0 focus:outline-none focus:ring-0" title="Chiudi" @click="isOpenDrawer = false">
                            <i class="fa-solid fa-xmark" />
                        </button>

                        <AsideMenu />
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
    <!-- / -->


    <!-- spinner -->
    <teleport to="#spinner">
        <div v-if="props.isLoading" class="fixed inset-0 z-[2000] flex items-center justify-center backdrop-blur-sm bg-black/30">
            <Spinner class="size-16 orange"/>
        </div>
    </teleport>
    <!-- / -->
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Tabs from '@/Components/Backoffice/Tabs.vue';
import Spinner from '@/Components/Spinner.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    title: String,
    icon: String,
    isLoading: Boolean,
    isSucess: Boolean,
    hasErrors: Boolean,
    backRoute: String,
    tabLinks: Object,
    as: {
        type: String,
        default: 'form'
    },
});

const emit = defineEmits(['submitted']);
const success = ref(props.isSucess || usePage().props.flash.success ? true : false);
const error = ref(props.hasErrors || usePage().props.flash.error ? true : false);
const isOpenDrawer = ref(false);

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
