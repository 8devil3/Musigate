<template>
    <Head title="SuonoErgoSono" />

    <div class="flex flex-col min-h-dvh justify-between bg-cover bg-[url(/public/img/banner_home.jpg)] bg-center">
        <Header />

        <div class="w-full max-w-4xl px-4 mx-auto pace-y-10 drop-shadow-md">
            <h1 class="uppercase text-[22px] md:text-3xl font-lemonLight mb-8">
                Cerchi una <span class="font-lemon uppercase text-[22px] md:text-3xl">sala prove</span> oppure<br class="hidden sm:inline">
                uno <span class="font-lemon uppercase text-[22px] md:text-3xl">studio di registrazione</span>?
            </h1>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-12">
                <form @submit.prevent="submit()" class="flex flex-col w-full gap-4 max-w-64">
                    <h2 class="pb-2 m-0 uppercase border-b-2 border-b-orange-500">iniza da qui</h2>

                    <ComboBox
                        v-model="form.location"
                        @selected="submit()"
                        :options="province"
                        placeholder="Digita una provincia"
                        inputIcon="fa-solid fa-location-dot"
                        listIcon="fa-solid fa-location-dot"
                    />
    
                    <!-- <div class="space-y-4">                        
                        <Input type="dateTime-local" v-model="form.start" label="Quando" :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" class="w-full" />

                        <div class="flex gap-2">
                            <NumberInput v-model="form.duration" :min="1" :max="24" label="Durata" unit="ore" class="grow" />
                            <NumberInput v-model="form.guests" :min="1" :max="99" label="Persone" class="grow" />
                        </div>
                    </div> -->
                    
                    <div class="w-full pt-4">
                        <Button type="submit" icon="fa-solid fa-magnifying-glass" text="cerca" class="w-full" />
                    </div>
                </form>

                <!-- <div class="flex flex-col max-w-[256px] gap-4">
                    <h2 class="pb-2 m-0 uppercase border-b-2 border-b-orange-500">Nuovo su Musigate?</h2>

                    <Button type="router" :href="route('register.studio.choose_plan')" text="Registra il tuo Studio" icon="fa-solid fa-microphone-lines" />
                    <Button type="router" :href="route('register')" text="Iscriviti come artista" icon="fa-solid fa-guitar" />
                </div> -->
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Frontoffice/Header.vue';
import Footer from '@/Components/Frontoffice/Footer.vue';
import Label from '@/Components/Form/Label.vue';
import Input from '@/Components/Form/Input.vue';
import ComboBox from '@/Components/Form/ComboBox.vue';
import Button from '@/Components/Form/Button.vue';
import province from './Search/province.json';
// import NumberInput from '@/Components/Form/NumberInput.vue';
// import Input from '@/Components/Form/Input.vue';
// import dayjs from 'dayjs';

const form = useForm({
    location: null,
    // start: dayjs().add(1, 'day').hour(Math.ceil(dayjs().hour())).minute(0).second(0).format('YYYY-MM-DD HH:mm'),
    // duration: 2,
    // guests: 1,
});

const submit = () => {
    form.get(route('rooms.index'))
};

</script>
