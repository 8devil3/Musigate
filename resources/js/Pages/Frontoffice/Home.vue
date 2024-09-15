<template>
    <FrontofficeLayout title="SuonoErgoSono">
        <div class="self-center w-full max-w-4xl px-4 my-24 space-y-10 md:pb-32 md:m-auto drop-shadow-md">
            <h1 class="uppercase text-[22px] md:text-3xl font-lemonLight">
                Cerchi una <span class="font-lemon uppercase text-[22px] md:text-3xl">sala prove</span> oppure<br class="hidden sm:inline">
                uno <span class="font-lemon uppercase text-[22px] md:text-3xl">studio di registrazione</span>?
            </h1>

            <form @submit.prevent="submit()" class="flex flex-col w-full max-w-xs gap-6">
                <h2 class="pb-1 uppercase border-b-2 border-b-orange-500">iniza da qui</h2>
                
                <SearchLocation v-model="form.location"/>

                <Button type="submit" icon="fa-solid fa-magnifying-glass" text="cerca" />
            </form>
        </div>
    </FrontofficeLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import Button from '@/Components/Form/Button.vue';
import SearchLocation from '@/Components/Frontoffice/Search/SearchLocation.vue';

const form = useForm({
    location: {},
    radius: 500,
})

const submit = () => {
    form.get(route('studio.index'))
}

</script>
