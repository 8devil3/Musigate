<template>
    <AuthLayout @submitted="submit()" title="Registrazione Studio" :isLoading="isLoading">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Registrazione Studio</h1>
            <h2 class="m-0 text-sm md:text-base font-lemon">
                Già registrato?
                <Link :href="route('login')" class="text-orange-500 hover:text-orange-400">
                    Accedi
                </Link>
            </h2>
        </template>

        <template #content>
            <!-- titolare -->
            <div v-if="props.step === 1" class="space-y-6">
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                        1/3 - Titolare dello Studio
                    </h2>

                    <Input v-model="formStep1.first_name" label="Nome" placeholder="Il nome del titolare dello Studio" :error="formStep1.errors.first_name"  />

                    <Input v-model="formStep1.last_name" label="Cognome" placeholder="Il cognome del titolare dello Studio" :error="formStep1.errors.last_name" required />
                </div>

                <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :isLoading="formStep1.processing" :disabled="formStep1.processing" class="w-full" />
            </div>
            <!-- / -->

            <!-- dati studio -->
            <div v-else-if="props.step === 2" class="space-y-6">
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                        2/3 - Dati Studio
                    </h2>
    
                    <Input v-model="formStep2.name" label="Nome" placeholder="Il nome dello Studio" :error="formStep2.errors.name" required />
    
                    <fieldset class="p-2 border border-slate-400 rounded-xl">
                        <legend class="text-xs font-normal px-1 text-slate-300 mb-0.5">
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
                    <Button type="router" :href="route('register.studio.step_1', props.studio_data)" icon="fa-solid fa-arrow-left" :isLoading="formStep2.processing" :disabled="formStep2.processing" title="Indietro" />

                    <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :isLoading="formStep2.processing" :disabled="formStep2.processing" />
                </div>
            </div>
            <!-- / -->

            <!-- account -->
            <div v-else-if="props.step === 3" class="space-y-6">
                <h2 class="pb-1 m-0 text-base border-b border-orange-500">
                    3/3 - Account
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
                    <Button type="router" :href="route('register.studio.step_2', props.studio_data)" icon="fa-solid fa-arrow-left" :isLoading="formStep3.processing" :disabled="formStep3.processing" title="Indietro" />

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
import GoogleLogin from './GoogleLogin.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';

const props = defineProps({
    step: Number,
    studio_data: Object,
});

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const formStep1 = useForm({
    step: 1,
    first_name: props.studio_data?.first_name ?? null,
    last_name: props.studio_data?.last_name ?? null,
});

const formStep2 = useForm({
    step: 2,
    name: props.studio_data?.name ?? null,
    category: props.studio_data?.category ?? null,
    vat: props.studio_data?.vat ?? null,
});

const formStep3 = useForm({
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