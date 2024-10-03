<template>
    <AuthLayout @submitted="submit()" title="Registrazione Studio" :isLoading="isLoading">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Registrazione Studio</h1>
        </template>

        <template #content>
            <!-- piano abbonamento -->
            <div v-if="props.step === 1" class="space-y-6">
                <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                    1/4 - Scegli un piano
                </h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- starter -->
                    <label for="subscription-starter" class="flex flex-col items-center gap-6 px-6 py-8 text-center transition-colors border-2 cursor-pointer hover:bg-slate-800/70 rounded-2xl" :class="formStep1.subscriptionPlan === 'starter' ? 'bg-slate-800/70 border-orange-500 shadow-inner' : 'bg-slate-900 border-slate-400'">
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
                                Contatto tramite 5 canali (Email, telefono, Telegram, WhatsApp, Messenger)
                            </li>
                        </ul>

                        <input type="radio" v-model="formStep1.subscriptionPlan" id="subscription-starter" name="choose-subscription" value="starter" class="hidden" />
                    </label>
                    <!-- / -->

                    <!-- business -->
                    <label for="subscription-business" class="flex flex-col items-center gap-6 px-6 py-8 text-center transition-colors border-2 cursor-pointer hover:bg-slate-800/70 rounded-2xl" :class="formStep1.subscriptionPlan === 'business' ? 'bg-slate-800/70 border-orange-500 shadow-inner' : 'bg-slate-900 border-slate-400'">
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
                            La scelta giusta per chi vuole fare sul serio!
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

                        <input type="radio" v-model="formStep1.subscriptionPlan" id="subscription-business" name="choose-subscription" value="business" class="hidden" />
                    </label>
                    <!-- / -->
                </div>
            </div>
            <!-- / -->

            <!-- titolare -->
            <div v-if="props.step === 2" class="space-y-6">
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                        2/4 - Rappresentante legale
                    </h2>

                    <Input v-model="formStep1.first_name" label="Nome" placeholder="Il nome del titolare dello Studio" :error="formStep1.errors.first_name"  />

                    <Input v-model="formStep1.last_name" label="Cognome" placeholder="Il cognome del titolare dello Studio" :error="formStep1.errors.last_name" required />

                    <!-- <Input type="date" v-model="formStep1.dob" label="Data di nascita" placeholder="La data di nascita" :error="formStep1.errors.dob" required />

                    <Input v-model="formStep1.cod_fiscale" pattern="[a-z][0-9]{16}" label="Codice fiscale" placeholder="Il codice fiscale" :error="formStep1.errors.cod_fiscale" required /> -->
                </div>

                <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :isLoading="formStep1.processing" :disabled="formStep1.processing" class="w-full" />
            </div>
            <!-- / -->

            <!-- dati studio -->
            <div v-else-if="props.step === 3" class="space-y-6">
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                        3/4 - Dati Studio
                    </h2>

                    <Input v-model="formStep2.name" label="Nome Studio" placeholder="Il nome dello Studio" :error="formStep2.errors.name" required />
    
                    <fieldset class="px-2 py-1 border border-slate-400 rounded-xl">
                        <legend class="px-1 text-xs font-normal text-slate-300">
                            Seleziona la categoria dello Studio
                        </legend>
                        
                        <div class="grid grid-cols-2 gap-4 p-2">
                            <Radio v-model="formStep2.category" name="register-studio-category" value="Professional" required>
                                Professional
                            </Radio>
                            <Radio v-model="formStep2.category" name="register-studio-category" value="Home">
                                Home
                            </Radio>
                        </div>
                    </fieldset>
                    
                    <Input inputmode="numeric" v-if="formStep2.category === 'Professional'" v-model="formStep2.vat" pattern="[0-9]{11}" label="Partita IVA" placeholder="La partita IVA della tua attività" :error="formStep2.errors.vat" required />
                </div>

                <div class="flex justify-between gap-6">
                    <Button type="router" :href="route('register.studio.step_1', props.studio_data)" icon="fa-solid fa-arrow-left" :isLoading="formStep2.processing" :disabled="formStep2.processing" text="Indietro" />

                    <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :isLoading="formStep2.processing" :disabled="formStep2.processing" />
                </div>
            </div>
            <!-- / -->

            <!-- account -->
            <div v-else-if="props.step === 4" class="space-y-6">
                <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                    4/4 - Account
                </h2>

                <div class="space-y-4">
                    <GoogleLogin />
    
                    <Input v-model="formStep3.email" type="email" label="Email" placeholder="La tua email" icon="fa-solid fa-envelope" :error="formStep3.errors.email" required autofocus />
    
                    <Input v-model="formStep3.password" type="password" label="Password" placeholder="La tua password" :error="formStep3.errors.password" required />
    
                    <Input v-model="formStep3.password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="formStep3.errors.password_confirmation" required/>
    
                    <!-- tos e privacy -->
                    <div class="px-4 space-y-4">
                        <Checkbox v-model="formStep3.tos" id="register-tos" required>
                            Accetto i <Link :href="tosLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Termini e Condizioni</Link>
                        </Checkbox>
    
                        <Checkbox v-model="formStep3.privacy" id="register-privacy" required>
                            Accetto la <Link :href="privacyLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Privacy policy</Link>
                        </Checkbox>
                    </div>
                    <!-- / -->
                </div>

                <div class="flex justify-between gap-6">
                    <Button type="router" :href="route('register.studio.step_2', props.studio_data)" icon="fa-solid fa-arrow-left" :isLoading="formStep3.processing" :disabled="formStep3.processing" text="Indietro" />

                    <Button text="Registrati" type="submit" icon="fa-solid fa-check" :isLoading="formStep3.processing" :disabled="formStep3.processing" color="green" />
                </div>
            </div>
            <!-- / -->
        </template>
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Radio from '@/Components/Form/Radio.vue';
import Input from '@/Components/Form/Input.vue';
import GoogleLogin from '../../GoogleLogin.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';

const props = defineProps({
    step: Number,
    studio_data: Object,
});

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const formStep1 = useForm({
    subscriptionPlan: 'business',
});

const formStep2 = useForm({
    step: 1,
    first_name: props.studio_data?.first_name ?? null,
    last_name: props.studio_data?.last_name ?? null,
    // cod_fiscale: props.studio_data?.cod_fiscale ?? null,
    // dob: props.studio_data?.dob ?? null,
});

const formStep3 = useForm({
    step: 2,
    name: props.studio_data?.name ?? null,
    category: props.studio_data?.category ?? null,
    vat: props.studio_data?.vat ?? null,
});

const formStep4 = useForm({
    step: 3,
    email: null,
    password: null,
    password_confirmation: null,
    tos: false,
    privacy: false,
});

const isLoading = ref(formStep1.processing || formStep2.processing || formStep3.processing)

const submit = () => {
    if(isLoading.value) return;

    if(props.step === 1) formStep1.get(route('register.studio.step_2'));
    else if(props.step === 2) formStep2.get(route('register.studio.step_3'));
    else if(props.step === 3) formStep3.post(route('register.studio.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>