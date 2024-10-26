<template>
    <Head title="Musigate | SuonoErgoSono" />

    <div>
        <Header />

        <div class="max-w-5xl px-4 pb-16 mx-auto md:pb-40 lg:px-6 xl:px-8">
            <section id="hero" class="grid grid-cols-1 py-8 gap-y-8 gap-x-16 md:py-20 md:pt-32 md:grid-flow-col md:auto-cols-auto min-h-[calc(100dvh-64px)] sm:min-h-full sm:h-auto">
                <img src="/img/logo/logo_vertical_complete.svg" alt="Musigate logo" class="h-32 mx-auto drop-shadow-lg md:hidden place-self-center">

                <div class="space-y-8 md:max-w-xl">
                    <h1 class="pb-2 uppercase border-b-2 border-b-orange-500">La musica prende forma</h1>

                    <h2>Promuovi il tuo Studio di registrazione e le tue Sale prova in tutta Italia.</h2>

                    <div>
                        <p>
                            Entra a far parte di Musigate: crea la pagina del tuo Studio e rendila pubblica fin da subito. Fai conoscere il tuo spazio a musicisti e creativi in tutta Italia!
                        </p>

                        <p>Al lancio del motore di ricerca, il tuo Studio sarà già visibile agli utenti, permettendoti di raggiungere ancora più persone.</p>

                        <p>Metti in luce il tuo Studio su Musigate e raggiungi musicisti in tutta Italia. Semplice da gestire e ricco di funzionalità avanzate, Musigate aiuta a connetterti con artisti e band alla ricerca del tuo spazio creativo.</p>

                        <p class="mb-4">Iscrivi gratuitamente il tuo Studio ora!</p>

                        <div class="text-center md:text-left">
                            <Button type="router" :href="route('register.studio.starter.step_1')" text="Iscriviti ora!" icon="fa-solid fa-right-to-bracket" />
                        </div>
                    </div>
                </div>

                <img src="/img/logo/logo_vertical_complete.svg" alt="Musigate logo" class="hidden mt-12 h-80 drop-shadow-lg md:block">
            </section>

            <section id="cosè-musigate" class="py-8 space-y-6 md:py-20">
                <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                    Cos'è Musigate
                </h2>

                <p>
                    Musigate è una piattaforma dedicata a Studi di registrazione, Sale prova e musicisti, progettata per semplificare la ricerca, la promozione e la gestione degli Studi. Che tu gestisca uno Studio Professional o un Home Studio, Musigate è pensato per supportarti con una suite di strumenti avanzati, dalle fasce orarie personalizzabili fino alle policy di annullamento.
                </p>
            </section>

            <section id="perché-scegliere-musigate" class="py-8 space-y-6 md:py-20">
                <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                    Perché scegliere Musigate
                </h2>

                <ul class="grid grid-cols-1 gap-6 p-0 m-0 list-none list-image-none sm:grid-cols-2 lg:grid-cols-3">
                    <li v-for="card in cards" class="!p-6 m-0 bg-slate-900/50 rounded-2xl">
                        <div class="text-center">
                            <i class="text-5xl text-slate-500" :class="card.icon" />
                        </div>
                        <div class="mt-12 space-y-2">
                            <h3>{{ card.title }}</h3>
                            <p class="text-slate-300">{{ card.description }}</p>
                        </div>
                    </li>
                </ul>
            </section>

            <section id="informazioni-minime" class="py-8 space-y-6 md:py-20">
                <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                    Informazioni minime
                </h2>

                <div class="space-y-4">
                    <p>Informazioni minime rischieste per la pubblicazione dello Studio:</p>
    
                    <ul class="grid grid-cols-1 list-musigate sm:grid-cols-2 md:grid-cols-3 gap-x-6">
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

            <section id="unisciti-a-musigate" class="py-8 space-y-6 md:py-20">
                <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                    Unisciti a Musigate
                </h2>

                <div class="flex flex-wrap justify-between gap-x-8 gap-y-4">
                    <div>
                        <p>Musigate è attualmente gratuito per tutti gli Studi.</p>
                        <p>Registrati ora e crea la tua pagina, entra a far parte di una community in crescita e fai conoscere il tuo Studio.</p>
                    </div>
                    <Button type="router" :href="route('register.studio.starter.step_1')" text="Iscriviti ora!" icon="fa-solid fa-right-to-bracket" class="shrink-0" />
                </div>
            </section>

            <section id="unisciti-a-musigate" class="py-8 space-y-6 md:py-20">
                <h2 class="pb-2 border-b-2 border-b-orange-500 font-lemon">
                    Dubbi, domande o perplessità
                </h2>

                <p>Per qualunque domanda o richiesta: <a href="mailto:assistenza@musigate.it" class="text-orange-500 underline transition-colors hover:text-orange-400">assistenza@musigate.it</a></p>
            </section>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import Header from '@/Components/Frontoffice/Header.vue';
import Footer from '@/Components/Frontoffice/Footer.vue';

const cards = [
    {
        icon: 'fa-solid fa-clock',
        title: 'Orari e fasce orarie',
        description: "Configura e personalizza le fasce orarie del tuo Studio per ciascun giorno della settimana. Imposta con precisione i periodi di apertura, suddividi le fasce orarie e duplica facilmente le impostazioni da un giorno all'altro per risparmiare tempo.",
    },
    {
        icon: 'fa-solid fa-clock-rotate-left',
        title: 'Policy di annullamento',
        description: "Definisci policy chiare e flessibili: imposta fino a due livelli di rimborso, stabilendo le ore entro le quali è possibile ottenere il rimborso parziale o totale. Informazioni chiare e sempre aggiornate per garantire trasparenza e professionalità.",
    },
    {
        icon: 'fa-solid fa-sliders',
        title: 'Gestione equipaggiamento',
        description: "Aggiungi la tua strumentazione, con l'opzione di inserimento singolo o in massa, per un setup rapido e senza errori. Ogni dettaglio è sotto controllo, con possibilità di aggiornare facilmente la lista.",
    },
    {
        icon: 'fa-solid fa-list-check',
        title: 'Informazioni minime',
        description: "Musigate richiede dei dati minimi per pubblicare lo Studio. Dalla descrizione dettagliata alle foto, passando per la location e i contatti: questi requisiti minimi sono verificati ad ogni aggiornamento, garantendo una piattaforma sempre completa e affidabile.",
    },
    {
        icon: 'fa-solid fa-image',
        title: 'Foto e contenuti',
        description: "Carica e riordina facilmente le foto del tuo Studio e delle sale, assicurando che i tuoi spazi siano presentati nel miglior modo possibile. Aggiungi i link social, le collaborazioni e i video di YouTube per offrire una panoramica artistica del tuo Studio.",
    },
    {
        icon: 'fa-solid fa-right-to-bracket',
        title: 'Accesso sicuro',
        description: "Registrati in pochi clic con email e password o con Google Login: avrai la possibilità di aggiornare, scollegare o eliminare il tuo account in qualsiasi momento.",
    },
];

</script>
