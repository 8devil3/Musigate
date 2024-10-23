<template>
    <Head :title="props.title" />

    <form @submit.prevent="emit('submitted')" class="flex p-4 md:p-6 grow bg-slate-950/30 md:bg-transparent">
        <fieldset :disabled="props.isLoading" class="flex flex-col w-full max-w-sm gap-8 m-auto md:backdrop-blur-md md:bg-slate-950/50">
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

    <Teleport to="#flash-message">
        <div v-if="usePage().props.flash.login_fail" class="fixed z-50 -translate-x-1/2 top-9 lg:top-4 left-1/2">
            <div class="flex items-start max-w-sm gap-2 px-3 py-1.5 text-sm font-normal text-white border-2 border-red-600 shadow-lg leading-tight rounded-xl bg-red-900">
                <i class="fa-solid fa-circle-exclamation mt-0.5" />
                {{ usePage().props.flash.login_fail }}
            </div>
        </div>
    </Teleport>

    <Teleport to="#flash-message">
        <div v-if="props.isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
            <Spinner class="size-16 orange" />
        </div>
    </Teleport>
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
