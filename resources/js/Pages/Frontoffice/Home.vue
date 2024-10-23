<template>
    <Head title="SuonoErgoSono" />

    <div class="flex flex-col bg-cover bg-[url(/public/img/banner_home.jpg)] bg-center sm:h-dvh">
        <div class="flex flex-col justify-between h-dvh sm:grow">
            <Header />
    
            <div class="w-full max-w-4xl px-4 mx-auto pace-y-10 drop-shadow-md">
                <h1 class="mb-8 text-2xl uppercase md:text-3xl font-lemonLight">
                    Cerchi una <span class="text-2xl uppercase font-lemon md:text-3xl">sala prove</span> oppure<br class="hidden sm:inline">
                    uno <span class="text-2xl uppercase font-lemon md:text-3xl">studio di registrazione</span>?
                </h1>
    
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-12">
                    <form @submit.prevent="submit()" class="flex flex-col gap-4 max-w-80">
                        <h2 class="pb-2 m-0 uppercase border-b-2 border-b-orange-500">inizia da qui</h2>
    
                        <ComboBox
                            v-model="form.location"
                            @selected="submit()"
                            :options="province"
                            label="Dove"
                            placeholder="Digita una provincia"
                            inputIcon="fa-solid fa-location-dot"
                            listIcon="fa-solid fa-location-dot"
                        />
    
                        <fieldset class="flex flex-wrap gap-6 px-3 py-2 border border-slate-400 bg-slate-900/30 rounded-2xl">
                            <legend class="px-1 text-xs font-semibold text-white">Categoria</legend>
                            <Radio v-model="form.category" name="search-studio-category" value="Professional">
                                Professional
                            </Radio>
                            <Radio v-model="form.category" name="search-studio-category" value="Home">
                                Home
                            </Radio>
                            <Radio v-model="form.category" name="search-studio-category" :value="null">
                                Entrambi
                            </Radio>
                        </fieldset>
    
                        <!-- <div class="space-y-4">                        
                            <Input type="dateTime-local" v-model="form.start" label="Quando" :min="dayjs().add(1, 'day').hour(0).minute(0).second(0).format('YYYY-MM-DD HH:mm')" :step="1800" class="w-full" />
    
                            <div class="flex gap-2">
                                <NumberInput v-model="form.duration" :min="1" :max="24" label="Durata" unit="ore" class="grow" />
                                <NumberInput v-model="form.guests" :min="1" :max="99" label="Persone" class="grow" />
                            </div>
                        </div> -->
    
                        <Button type="submit" icon="fa-solid fa-magnifying-glass" text="cerca" class="w-full" />
                    </form>
                </div>
            </div>
    
            <div class="h-16" />
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
import Radio from '@/Components/Form/Radio.vue';
import province from './Search/province.json';
// import NumberInput from '@/Components/Form/NumberInput.vue';
// import Input from '@/Components/Form/Input.vue';
// import dayjs from 'dayjs';

const form = useForm({
    location: null,
    category: null,
    // start: dayjs().add(1, 'day').hour(Math.ceil(dayjs().hour())).minute(0).second(0).format('YYYY-MM-DD HH:mm'),
    // duration: 2,
    // guests: 1,
});

const submit = () => {
    form.get(route('studio.index'))
};

</script>
