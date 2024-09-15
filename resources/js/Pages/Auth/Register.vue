<template>
    <AuthLayout @submitted="submit()" title="Registrazione" :isLoading="form.processing">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Registra il tuo Studio</h1>
            <h2 class="m-0 text-sm md:text-base font-lemon">
                Già registrato?
                <Link :href="route('login.create')" class="text-orange-500 hover:text-orange-400">
                    Accedi
                </Link>
            </h2>
        </template>

        <template #content>
            <!-- account -->
            <div class="space-y-4">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">Account</h2>

                <Input v-model="form.email" id="register-email" type="email" label="Email" placeholder="La tua email" icon="fa-solid fa-envelope" :error="form.errors.email" required autofocus />
                
                <Input v-model="form.password" id="register-password" type="password" label="Password" placeholder="La tua password" :error="form.errors.password" required />
    
                <Input v-model="form.password_confirmation" id="register-password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="form.errors.password_confirmation" required/>
            </div>
            <!-- / -->

            <!-- titolare -->
            <div class="pt-8 space-y-4">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">Titolare</h2>

                <Input v-model="form.first_name" id="register-first-name" label="Nome" placeholder="Il nome del titolare dello Studio" :error="form.errors.first_name" required />

                <Input v-model="form.last_name" id="register-last-name" label="Cognome" placeholder="Il cognome del titolare dello Studio" :error="form.errors.last_name" required />
            </div>
            <!-- / -->

            <!-- studio -->
            <div class="pt-8 space-y-4">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">Studio</h2>

                <Input v-model="form.studio_name" id="register-studio-name" label="Nome Studio" placeholder="Il nome dello Studio" :error="form.errors.studio_name" required />

                <fieldset class="p-2 border border-gray-400 rounded-lg">
                    <legend class="text-xs font-semibold px-1 text-gray-300 mb-0.5">Seleziona la categoria dello Studio</legend>
                    
                    <div class="grid grid-cols-2 gap-4 p-2">
                        <Radio v-model="form.category" name="register-studio-category" value="Professional" required>
                            Professional
                        </Radio>
                        <Radio v-model="form.category" name="register-studio-category" value="Home">
                            Home
                        </Radio>
                    </div>
                </fieldset>
                
                <Input v-if="form.category === 'Professional'" v-model="form.vat" pattern="[0-9]{11}" id="register-vat" label="Partita IVA" placeholder="La partita IVA della tua attività" :error="form.errors.vat" required />
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

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    studio_name: null,
    category: null,
    vat: null,
    tos: false,
    privacy: false,
});

const submit = () => {
    form.post(route('register.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>