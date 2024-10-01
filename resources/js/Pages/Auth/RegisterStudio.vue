<template>
    <AuthLayout @submitted="submit()" title="Registrazione Studio" :isLoading="form.processing">
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
            <div v-if="props.step === 1" class="space-y-6">
                <!-- titolare -->
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">Titolare</h2>
    
                    <Input v-model="form.first_name" label="Nome" placeholder="Il nome del titolare dello Studio" :error="form.errors.first_name"  />
    
                    <Input v-model="form.last_name" label="Cognome" placeholder="Il cognome del titolare dello Studio" :error="form.errors.last_name" required />
                </div>
                <!-- / -->
    
                <!-- studio -->
                <div class="pt-4 space-y-4">
                    <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">Studio</h2>
    
                    <Input v-model="form.name" label="Nome Studio" placeholder="Il nome dello Studio" :error="form.errors.name" required />
    
                    <fieldset class="p-2 border border-slate-400 rounded-xl">
                        <legend class="text-xs font-normal px-1 text-slate-300 mb-0.5">Seleziona la categoria dello Studio</legend>
                        
                        <div class="grid grid-cols-2 gap-4 p-2">
                            <Radio v-model="form.category" name="register-studio-category" value="Professional" required>
                                Professional
                            </Radio>
                            <Radio v-model="form.category" name="register-studio-category" value="Home">
                                Home
                            </Radio>
                        </div>
                    </fieldset>
                    
                    <Input inputmode="numeric" v-if="form.category === 'Professional'" v-model="form.vat" pattern="[0-9]{11}" label="Partita IVA" placeholder="La partita IVA della tua attività" :error="form.errors.vat" required />
                </div>
                <!-- / -->

                <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :isLoading="form.processing" :disabled="form.processing" class="w-full" />
            </div>

            <div v-else-if="props.step === 2" class="space-y-6">
                <BackLink label="Indietro" :href="route('register.studio.step_1')" />

                <GoogleLogin />

                <Input v-model="form.email" type="email" label="Email" placeholder="La tua email" icon="fa-solid fa-envelope" :error="form.errors.email" required autofocus />

                <Input v-model="form.password" type="password" label="Password" placeholder="La tua password" :error="form.errors.password" required />

                <Input v-model="form.password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="form.errors.password_confirmation" required/>

                <!-- tos e privacy -->
                <div class="px-4 space-y-4">
                    <Checkbox v-model="form.tos" id="register-tos" required>
                        Accetto i <Link :href="tosLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Termini e Condizioni</Link>
                    </Checkbox>

                    <Checkbox v-model="form.privacy" id="register-privacy" required>
                        Accetto la <Link :href="privacyLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Privacy policy</Link>
                    </Checkbox>
                </div>
                <!-- / -->

                <Button text="Registrati" type="submit" :isLoading="form.processing" :disabled="form.processing" class="w-full" />
            </div>
        </template>
    </AuthLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Radio from '@/Components/Form/Radio.vue';
import Input from '@/Components/Form/Input.vue';
import GoogleLogin from './GoogleLogin.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import BackLink from '@/Components/BackLink.vue';

const props = defineProps({
    step: Number,
    request: Object,
});

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const form = useForm({
    first_name: props.request?.first_name ?? null,
    last_name: props.request?.last_name ?? null,
    name: props.request?.name ?? null,
    category: props.request?.category ?? null,
    vat: props.request?.vat ?? null,

    email: props.request?.email ?? null,
    password: props.request?.password ?? null,
    password_confirmation: props.request?.password_confirmation ?? null,
    tos: props.request?.tos ?? false,
    privacy: props.request?.privacy ?? false,
});

const submit = () => {
    if(form.processing) return;
    if(props.step === 1) form.post(route('register.studio.step_2'));
    else if(props.step === 2) form.post(route('register.studio.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>