<template>
    <Head :title="props.title" />

    <form @submit.prevent="emit('submitted')" class="flex p-6 grow bg-gray-950/30 md:bg-transparent">
        <fieldset :disabled="props.isLoading" class="flex flex-col w-full max-w-xs gap-8 px-6 py-12 m-auto md:backdrop-blur-md md:bg-gray-950/50">
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

    <div v-show="props.isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
        <Spinner class="w-16 h-16 orange" />
    </div>
</template>

<script setup>
import Spinner from '@/Components/Spinner.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    isLoading: Boolean,
    status: String,
});

const emit = defineEmits(['submitted']);

</script>
