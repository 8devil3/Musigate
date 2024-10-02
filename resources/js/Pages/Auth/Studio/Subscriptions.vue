<template>
    <RegisterStudioLayout title="Scegli un piano" :isLoading="isLoading">
        <template #title>
            Scegli un piano
        </template>

        <template #content>
            <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                Scegli un piano
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- starter -->
                <Link :href="route('register.studio.starter.step_1')" class="flex flex-col items-center gap-6 px-6 py-8 text-center transition-colors border-2 hover:border-orange-500 hover:bg-slate-800/70 rounded-2xl bg-slate-900 border-slate-400">
                    <img src="/img/logo/logo_mobile.svg" class="w-12" />

                    <h3 class="p-0 m-0 text-2xl text-center text-white uppercase font-lemonLight">
                        starter
                    </h3>

                    <h4 class="p-0 m-0 text-base font-normal text-center font-lemon text-slate-200">
                        Gratuito
                    </h4>

                    <p class="px-4 py-1.5 text-sm text-white uppercase rounded-full shadow-md bg-slate-600 font-lemon">
                        iscriviti ora
                    </p>
                    
                    <p class="text-sm">
                        Perfetto per iniziare senza vincoli!
                    </p>

                    <ul class="mx-auto list-musigate">
                        <li class="pb-2 list-musigate">
                            Pubblica il tuo Studio, Sale prova e servizi
                        </li>
                        <li class="pb-2 list-musigate">
                            Contatto tramite 5 canali: email, telefono, Telegram, WhatsApp, Messenger
                        </li>
                    </ul>
                </Link>
                <!-- / -->

                <!-- business -->
                <Link :href="route('register.studio.business.step_1')" class="flex flex-col items-center gap-6 px-6 py-8 text-center transition-colors border-2 hover:border-orange-500 hover:bg-slate-800/70 rounded-2xl bg-slate-900 border-slate-400">
                    <img src="/img/logo/logo_gold.svg" class="w-12" />

                    <h3 class="p-0 m-0 text-2xl text-center text-white uppercase font-lemonLight">
                        business
                    </h3>

                    <h4 class="p-0 m-0 text-base text-center font-lemon text-slate-200">
                        € 599/anno
                    </h4>

                    <p class="px-4 py-1.5 text-sm text-white uppercase bg-orange-500 rounded-full shadow-md font-lemon">
                        abbonati ora
                    </p>

                    <p class="text-sm">
                        La scelta dei professionisti!
                    </p>

                    <ul class="mx-auto list-musigate">
                        <li class="pb-2 list-musigate">
                            Pubblica il tuo  Studio, Sale prova e servizi
                        </li>
                        <li class="pb-2 list-musigate">
                            Ricevi prenotazioni e incassa senza commissioni 
                        </li>
                        <li class="pb-2 list-musigate">
                            Gestisci gli annullamenti e i rimborsi 
                        </li>
                        <li class="pb-2 list-musigate">
                            Sincronizza i calendari (solo Google Calendar)
                        </li>
                        <li class="pb-2 list-musigate">
                            Priorità nelle ricerche
                        </li>
                        <li class="list-musigate">
                            Badge logo "M" d'oro
                        </li>
                    </ul>
                </Link>
                <!-- / -->
            </div>
        </template>
    </RegisterStudioLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import RegisterStudioLayout from '@/Layouts/Register/RegisterStudioLayout.vue';

const isLoading = ref(false);

router.on('start', ()=> isLoading.value = true);
router.on('finish', ()=> isLoading.value = false);

</script>