<template>
    <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="h-full lg:bg-slate-900">
        <fieldset :disabled="props.isLoading" class="flex flex-col h-full">
            <h1 v-if="props.title" class="z-10 flex items-center justify-between h-16 gap-4 px-6 m-0 text-base border-0 border-b-2 shadow-md shrink-0 bg-slate-800 md:text-xl border-slate-800">
                <div class="flex items-center w-full gap-2 leading-tight md:gap-3">
                    <Link v-if="props.backRoute && props.showBackRoute" :href="props.backRoute">
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

                <p v-if="$slots.description" class="pb-6 text-sm border-b text-slate-300 border-slate-800">
                    <slot name="description"/>
                </p>

                <slot name="content"/>
            </div>
        </fieldset>
    </component>

    <!-- messaggi flash e spinner -->
    <div class="fixed -translate-x-1/2 top-9 lg:top-4 left-1/2 z-[2000]">
        <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="props.onSuccess" class="flex items-center gap-2 px-4 py-3 text-sm font-medium leading-none text-white border-2 rounded-full shadow-lg bg-emerald-600/20 border-emerald-500">
                <i class="fa-solid fa-check" />
                {{ usePage().props.flash.success }}
            </div>
        </transition>

        <div v-show="props.onFail" class="flex items-center gap-2 px-4 py-3 text-sm font-medium leading-none text-white border-2 border-red-600 rounded-full shadow-lg bg-red-600/20">
            <i class="fa-solid fa-xmark" />
            Salvataggio non riuscito!
        </div>
    </div>

    <div v-if="props.isLoading" class="fixed inset-0 z-[1000] flex items-center justify-center backdrop-blur-sm bg-black/30">
        <Spinner class="w-16 h-16 orange"/>
    </div>
    <!-- / -->
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Tabs from '@/Components/Backoffice/Tabs.vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    title: String,
    icon: String,
    isLoading: Boolean,
    onSuccess: Boolean,
    onFail: Boolean,
    backRoute: String,
    showBackRoute: Boolean,
    flashMessage: {
        type: String,
        default: 'Salvato!'
    },
    as: {
        type: String,
        default: 'form'
    },
    tabLinks: Object,
});

const emit = defineEmits(['submitted']);

</script>
