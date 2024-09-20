<template>
    <FrontofficeLayout title="SuonoErgoSono">
        <div class="self-center w-full max-w-4xl px-4 my-24 space-y-10 md:pb-32 md:m-auto drop-shadow-md">
            <h1 class="uppercase text-[22px] md:text-3xl font-lemonLight">
                Cerchi una <span class="font-lemon uppercase text-[22px] md:text-3xl">sala prove</span> oppure<br class="hidden sm:inline">
                uno <span class="font-lemon uppercase text-[22px] md:text-3xl">studio di registrazione</span>?
            </h1>

            <form @submit.prevent="submit()" class="flex flex-col w-full max-w-xs gap-4">
                <h2 class="pb-2 m-0 uppercase border-b-2 border-b-orange-500">iniza da qui</h2>
                
                <div>
                    <Label for="where" label="Dove" />
                    <SearchLocation id="where" v-model="form.location"/>
                </div>

                <div class="space-y-4">
                    <div>
                        <Label for="when" label="Quando" />
                        <div id="when" class="flex gap-2">
                            <Input type="date" v-model="form.date" required class="w-full" />
                            <Input type="time" v-model="form.time" :step="1800" required class="w-28" />
                        </div>
                    </div>
                    
                    <NumberInput v-model="form.duration" label="Durata (ore)" required />
                </div>

                <div class="w-full pt-4">
                    <Button type="submit" icon="fa-solid fa-magnifying-glass" text="cerca" class="w-full" />
                </div>
            </form>
        </div>
    </FrontofficeLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Label from '@/Components/Form/Label.vue';
import Input from '@/Components/Form/Input.vue';
import SearchLocation from '@/Components/Frontoffice/Search/SearchLocation.vue';
import dayjs from 'dayjs';
import NumberInput from '@/Components/Form/NumberInput.vue';

const form = useForm({
    location: {},
    radius: 500,
    date: dayjs().format('YYYY-MM-DD'),
    time: dayjs().minute(0).format('HH:mm'),
    duration: 2.
});

const submit = () => {
    form.get(route('studio.index'))
};

</script>
