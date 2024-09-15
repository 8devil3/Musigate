<template>
    <Head :title="props.title"/>

    <Header />
    
    <div class="flex-col flex md:flex-row lg:h-[calc(100dvh-64px)] overflow-hidden bg-gray-800 lg:bg-gray-950 w-full grow">        
        <AsideMenu class="hidden w-56 lg:w-64 lg:flex shrink-0" />
        
        <main class="w-full max-w-6xl mx-auto overflow-hidden grow lg:p-8">
            <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="h-full lg:bg-gray-900">
                <fieldset :disabled="props.isLoading" class="flex flex-col h-full">
                    <h1 v-if="props.title" class="shrink-0 flex items-center justify-between shadow-md z-10 h-14 px-4 gap-4 m-0 text-[16px] lg:bg-gray-800 bg-gray-900 md:text-xl lg:h-20 lg:px-6 border-b-2 border-gray-800 lg:border-0">
                        <div class="flex items-center w-full gap-2 leading-tight md:gap-3">
                            <Link v-if="props.backRoute" :href="props.backRoute" class="lg:hidden">
                                <i class="mr-2 text-lg text-orange-500 fa-solid fa-chevron-left"></i>
                            </Link>

                            <i v-if="props.icon" :class="props.icon"></i>

                            {{ props.title }}
                        </div>
                        
                        <div v-if="$slots.actions" class="fixed lg:static bottom-20 right-4">
                            <slot name="actions"/>
                        </div>

                        <UserMenu class="lg:hidden shrink-0" />
                    </h1>
            
                    <div v-if="$slots.content" class="p-4 space-y-8 overflow-y-auto grow lg:p-6" :class="{'pb-20' : $slots.actions}">
                        <Tabs v-if="props.tabLinks" :tabLinks="tabLinks"/>

                        <p v-if="$slots.description" class="pb-6 text-gray-300 border-b border-gray-600">
                            <slot name="description"/>
                        </p>                       

                        <slot name="content"/>
                    </div>
                </fieldset>
            </component>
        </main>
    </div>

    <!-- app bar -->
    <AppBar />
    <!-- / -->

    <!-- messaggi flash e spinner -->
    <div class="fixed -translate-x-1/2 top-9 lg:top-4 left-1/2 z-[2000]">
        <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="props.onSuccess" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold leading-none text-white rounded-lg shadow-lg bg-emerald-500">
                <i class="fa-solid fa-check"></i>
                {{ props.flashMessage }}
            </div>
        </transition>

        <div v-show="props.onFail" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold leading-none text-white bg-red-600 rounded-lg shadow-lg">
            <i class="fa-solid fa-xmark"></i>
            Non riuscito!
        </div>
    </div>

    <div v-if="props.isLoading" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/30">
        <Spinner class="w-16 h-16 orange"/>
    </div>
    <!-- / -->
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Spinner from '@/Components/Spinner.vue';
import Tabs from '@/Components/Backoffice/Tabs.vue';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Header from '@/Components/Backoffice/Header.vue';
import AppBar from '@/Components/Backoffice/AppBar.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const props = defineProps({
    title: String,
    subTitle: String,
    isLoading: Boolean,
    onSuccess: Boolean,
    onFail: Boolean,
    flashMessage: {
        type: String,
        default: 'Salvato!'
    },
    as: {
        type: String,
        default: 'form'
    },
    icon: {
        type: String,
        default: null
    },
    backRoute: {
        type: String,
        default: null
    },
    tabLinks: Object
});

const emit = defineEmits(['submitted']);

</script>