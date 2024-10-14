<template>
    <Head :title="props.title" />

    <form @submit.prevent="emit('submitted')" class="flex p-4 md:p-6 grow bg-gray-950/30 md:bg-transparent">
        <fieldset :disabled="props.isLoading" class="flex flex-col w-full max-w-sm gap-8 m-auto md:backdrop-blur-md md:bg-gray-950/50">
            <Link :href="route('home')" class="block mx-auto">
                <img src="/img/logo/logo_vertical_complete.svg" alt="Musigate logo" class="h-24 md:h-32">
            </Link>

            <div class="text-center">
                <slot name="head" />
            </div>
            
            <div class="space-y-4">
                <div v-if="props.status" class="p-2 text-sm font-medium text-center text-white rounded-lg bg-emerald-600">
                    {{ props.status }}
                </div>
                <slot name="content" />
            </div>

            <slot name="actions" />
        </fieldset>
    </form>

    <div class="fixed -translate-x-1/2 top-9 lg:top-4 left-1/2 z-[2000]">
        <transition leave-active-class="transition duration-1000 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-show="props.onSuccess" class="flex items-center gap-2 px-4 py-2 text-sm font-normal leading-none text-white border-2 rounded-full shadow-lg bg-emerald-600/20 border-emerald-500">
                <i class="fa-solid fa-check" />
                {{ usePage().props.flash.success }}
            </div>
        </transition>

        <div v-show="usePage().props.flash.error" class="flex items-start max-w-sm gap-2 px-2.5 py-2 text-sm font-normal text-white border-2 border-red-600 shadow-lg rounded-xl bg-red-600/20">
            <i class="mt-1 fa-solid fa-circle-exclamation" />
            {{ usePage().props.flash.error }}
        </div>
    </div>

    <div v-show="props.isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
        <Spinner class="size-16 orange" />
    </div>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    title: String,
    isLoading: Boolean,
    status: String,
    onSuccess: Boolean,
    onFail: Boolean,
});

const emit = defineEmits(['submitted']);

</script>
