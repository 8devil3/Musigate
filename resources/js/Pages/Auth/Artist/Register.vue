<template>
    <AuthLayout @submitted="submit()" title="Registrazione" :isLoading="form.processing">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Registrati</h1>
            <h2 class="m-0 text-sm md:text-base font-lemon">
                Già registrato?
                <Link :href="route('login')" class="text-orange-500 hover:text-orange-400">
                    Accedi
                </Link>
            </h2>
        </template>

        <template #content>
            <!-- account -->
            <div class="space-y-6">
                <GoogleLogin />

                <div class="space-y-4">
                    <Input v-model="form.first_name" label="Nome" placeholder="Il tuo nome" :error="form.errors.first_name" required />
    
                    <Input v-model="form.last_name" label="Cognome" placeholder="Il tuo cognome" :error="form.errors.last_name" required />
    
                    <Input v-model="form.email" type="email" label="Email" placeholder="La tua email" icon="fa-solid fa-envelope" :error="form.errors.email" required autofocus />
                    
                    <Input v-model="form.password" type="password" label="Password" placeholder="La tua password" :error="form.errors.password" required />
        
                    <Input v-model="form.password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="form.errors.password_confirmation" required/>
                </div>
            </div>
            <!-- / -->

            <!-- tos e privacy -->
            <div class="pt-8">
                <div class="px-4 pt-4 space-y-4 border-t border-orange-500">
                    <Checkbox v-model="form.tos" id="register-tos" required>
                        Accetto i <Link :href="tosLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Termini e Condizioni</Link>
                    </Checkbox>
    
                    <Checkbox v-model="form.privacy" id="register-privacy" required>
                        Accetto la <Link :href="privacyLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Privacy policy</Link>
                    </Checkbox>
                </div>
            </div>
            <!-- / -->
        </template>

        <template #actions>
            <Button text="Registrati" type="submit" :isLoading="form.processing"/>
        </template>
    </AuthLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Radio from '@/Components/Form/Radio.vue';
import Input from '@/Components/Form/Input.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import GoogleLogin from '../GoogleLogin.vue';

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    tos: false,
    privacy: false,
});

const submit = () => {
    form.post(route('register.artist.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>