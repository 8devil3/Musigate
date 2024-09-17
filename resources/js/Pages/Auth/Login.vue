<template>
    <AuthLayout @submitted="submit()" title="Accesso" :isLoading="form.processing" :status="props.status">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Accedi al tuo account</h1>
            <h2 class="text-sm md:text-base font-lemon">
                Non ancora iscritto?
                <Link :href="route('register.create')" class="text-orange-500 hover:text-orange-400">
                    Iscriviti
                </Link>
            </h2>
        </template>

        <template #content>
            <GoogleLogin />

            <Input v-model="form.email" id="login-email" type="email" label="Email" placeholder="La tua email" icon="fa-solid fa-envelope" :error="form.errors.email" required autofocus class="w-full" />
            
            <Input v-model="form.password" id="login-password" type="password" label="Password" placeholder="La tua password" :error="form.errors.password" required class="w-full" />

            <div class="flex items-center justify-between px-4">
                <Checkbox id="login-remember" v-model:checked="form.remember">
                    Ricordami
                </Checkbox>
                
                <Link v-model="form.password" v-if="canResetPassword" :href="route('password.request')" class="block text-xs font-medium text-right text-orange-500 hover:text-orange-400">Password dimenticata?</Link>
            </div>
        </template>

        <template #actions>
            <Button text="Accedi" type="submit" :isLoading="form.processing"/>
        </template>
    </AuthLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import GoogleLogin from './GoogleLogin.vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

const submit = () => {
    form.post(route('login.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>