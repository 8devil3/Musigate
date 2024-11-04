<template>
    <Head title="SuonoErgoSono" />

    <div class="h-dvh bg-cover bg-[url('/img/bgHome.jpg')] bg-center">
        <div class="h-full overflow-y-auto bg-black/30">
            <Header />
        
            <div class="max-w-5xl px-4 mx-auto space-y-8 md:space-y-16 md:px-6">
                <section class="flex flex-col justify-center pb-8 sm:pb-0 drop-shadow-md h-[calc(100dvh-64px)] sm:h-[480px] xl:h-[540px]">
                    <h1 class="mb-8 text-2xl uppercase md:text-3xl font-lemonLight">
                        Cerchi una <span class="text-2xl uppercase font-lemon md:text-3xl">sala prove</span> oppure<br class="hidden sm:inline">
                        uno <span class="text-2xl uppercase font-lemon md:text-3xl">studio di registrazione</span>?
                    </h1>
        
                    <form @submit.prevent="submit()" class="space-y-4 max-w-80">
                        <h2 class="pb-2 m-0 uppercase border-b-2 border-b-orange-500">inizia da qui</h2>
        
                        <!-- <ComboBox
                            v-model="form.location"
                            @selected="submit()"
                            :options="province"
                            label="Dove"
                            placeholder="Digita una provincia"
                            inputIcon="fa-solid fa-location-dot"
                            listIcon="fa-solid fa-location-dot"
                        /> -->
        
                        <fieldset class="flex flex-wrap gap-6 p-3 border border-slate-400 bg-slate-900/50 rounded-2xl">
                            <legend class="px-1 text-xs font-medium text-white">Categoria</legend>
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
                </section>
        
                <section id="ultimi-studi" class="space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Ultimi Studi pubblicati
                    </h2>
        
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
                        <StudioCard v-for="studio in props.latest_studios" :studio="studio" />
                    </div>
                </section>
        
                <section id="cosè-musigate" class="space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Cos'è Musigate
                    </h2>
        
                    <p>
                        Musigate è una piattaforma dedicata a Studi di registrazione, Sale prova e musicisti, progettata per semplificare la ricerca, la promozione e la gestione degli Studi. Che tu gestisca uno Studio Professional o un Home Studio, Musigate è pensato per supportarti con una suite di strumenti avanzati, dalle fasce orarie personalizzabili fino alle policy di annullamento.
                    </p>
                </section>
        
                <section id="cosa-offre-musigate" class="space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Musigate per gli Studi
                    </h2>
        
                    <ul class="grid grid-cols-1 gap-6 p-0 m-0 list-none list-image-none sm:grid-cols-2 lg:grid-cols-3">
                        <li v-for="card in cards" class="!p-6 m-0 bg-slate-900/70 border border-slate-400 rounded-2xl">
                            <div class="pt-4 text-center">
                                <i class="text-5xl text-slate-500" :class="card.icon" />
                            </div>
                            <div class="mt-12 space-y-2">
                                <h3>{{ card.title }}</h3>
                                <p class="text-slate-300">{{ card.description }}</p>
                            </div>
                        </li>
                    </ul>
                </section>
        
                <section id="informazioni-minime" class="space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Informazioni minime
                    </h2>
        
                    <div class="space-y-4">
                        <p>Informazioni minime rischieste per la pubblicazione dello Studio:</p>
        
                        <ul class="gap-6 list-musigate sm:columns-2 md:columns-3">
                            <li>il nome dello Studio</li>
                            <li>almeno una Sala o un pacchetto pubblicato</li>
                            <li>aperto almeno un giorno della settimana</li>
                            <li>la partita iva (solo se di categoria Professional)</li>
                            <li>una presentazione di almeno 100 caratteri, spazi esclusi</li>
                            <li>la location</li>
                            <li>almeno un metodo di pagamento</li>
                            <li>almeno quattro foto</li>
                            <li>almeno un canale di contatto (canali disponibili: email, telefono, Whatsapp, Messenger, Telegram)</li>
                        </ul>
                    </div>
                </section>
        
                <section id="unisciti-a-musigate" class="space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Unisciti a Musigate
                    </h2>
        
                    <div class="flex flex-wrap justify-between gap-x-8 gap-y-4">
                        <div>
                            <p>Registrati ora e crea la tua pagina, entra a far parte di una community in crescita e fai conoscere il tuo Studio.</p>
                            <p>Musigate è gratuito per tutti gli Studi.</p>
                        </div>
                        <Button type="router" :href="route('register.studio.starter.step_1')" text="Iscriviti ora!" icon="fa-solid fa-right-to-bracket" class="shrink-0" />
                    </div>
                </section>
        
                <section id="dubbi-domande" class="pb-12 space-y-6">
                    <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                        Dubbi, domande o perplessità
                    </h2>
        
                    <p>Per qualunque domanda o richiesta: <a href="mailto:assistenza@musigate.it" class="text-orange-500 underline transition-colors hover:text-orange-400">assistenza@musigate.it</a></p>
                </section>
            </div>
        
            <Footer />
        </div>
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
import StudioCard from './Search/StudioCard.vue';
import province from './Search/province.json';
// import NumberInput from '@/Components/Form/NumberInput.vue';
// import Input from '@/Components/Form/Input.vue';
// import dayjs from 'dayjs';

const props = defineProps({
    latest_studios: Object,
});

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

const cards = [
    {
        icon: 'fa-solid fa-clock',
        title: 'Orari e fasce orarie',
        description: "Configura e personalizza le fasce orarie del tuo Studio per ciascun giorno della settimana. Imposta con precisione i periodi di apertura, suddividi le fasce orarie e duplica facilmente le impostazioni da un giorno all'altro per risparmiare tempo.",
    },
    {
        icon: 'fa-solid fa-euro',
        title: 'Modelli tariffari',
        description: "Gestisci con flessibilità i modelli tariffari delle tue Sale e Pacchetti. Imposta opzioni senza tariffa, scegli una tariffa oraria, mensile o basata su fasce orarie. Ogni modello è personalizzabile per garantire che le tue offerte rispecchino al meglio le esigenze del tuo Studio e della tua clientela.",
    },
    {
        icon: 'fa-solid fa-clock-rotate-left',
        title: 'Policy di annullamento',
        description: "Definisci policy chiare e flessibili: imposta fino a due livelli di rimborso, stabilendo le ore entro le quali è possibile ottenere il rimborso parziale o totale e la percentuale. Informazioni chiare e sempre aggiornate per garantire trasparenza e professionalità.",
    },
    {
        icon: 'fa-solid fa-sliders',
        title: 'Equipaggiamento',
        description: "Aggiungi la strumentazione, con l'opzione di inserimento singolo o in massa, per un setup rapido e senza errori. Ogni dettaglio è sotto controllo, con possibilità di aggiornare facilmente la lista.",
    },
    {
        icon: 'fa-solid fa-image',
        title: 'Foto e contenuti',
        description: "Carica e riordina facilmente le foto del tuo Studio e delle sale, assicurando che i tuoi spazi siano presentati nel miglior modo possibile. Aggiungi i link social, le collaborazioni e i video di YouTube per offrire una panoramica artistica del tuo Studio.",
    },
    {
        icon: 'fa-solid fa-list-check',
        title: 'Informazioni minime',
        description: "Musigate richiede dei dati minimi per pubblicare lo Studio. Dalla descrizione dettagliata alle foto, passando per la location e i contatti: questi requisiti minimi sono verificati ad ogni aggiornamento, garantendo una piattaforma sempre completa e affidabile.",
    },
];

</script>
