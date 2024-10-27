<template>
    <Head :title="props.title" />

    <form @submit.prevent="emit('submitted')" class="flex p-6 md:p-8 grow">
        <fieldset :disabled="props.isLoading" class="flex flex-col w-full max-w-[640px] gap-8 m-auto">
            <Link :href="route('home')" class="block mx-auto">
                <img src="/img/logo/logo_vertical_complete.svg" alt="Musigate logo" class="h-24 md:h-32">
            </Link>

            <div>
                <h1 class="text-lg text-center uppercase md:text-xl font-lemon">Iscrizione Studio</h1>
                <h2 v-if="props.plan" class="m-0 text-sm text-center uppercase md:text-base font-lemonLight">{{ props.plan }}</h2>
            </div>

            <slot name="content" />
        </fieldset>
    </form>

    <div v-show="props.isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
        <Spinner class="size-16 orange" />
    </div>
</template>

<script setup>
import Spinner from '@/Components/Spinner.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    isLoading: Boolean,
    plan: String,
});

const emit = defineEmits(['submitted']);

</script>
