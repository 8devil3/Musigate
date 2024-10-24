<template>
    <AuthLayout @submitted="submit()" title="Accesso" :isLoading="form.processing" :status="props.status">
        <template #head>
            <h1 class="text-lg uppercase md:text-xl font-lemon">Accesso</h1>
            <h3 class="text-sm">
                Non ancora iscritto? <Link :href="route('register.studio.starter.step_1')" class="text-orange-500 transition-colors hover:text-orange-400">
                    Registrati ora
                </Link>
            </h3>
        </template>

        <template #content>
            <GoogleLogin />

            <Input type="email" v-model="form.email" label="Email" placeholder="La tua email" :error="form.errors.email" required class="w-full" />
            
            <Input type="password" v-model="form.password" label="Password" placeholder="La tua password" :error="form.errors.password" required class="w-full" />

            <div class="flex items-center justify-between px-4">
                <Checkbox v-model:checked="form.remember">
                    Ricordami
                </Checkbox>
                
                <Link v-if="canResetPassword" v-model="form.password" :href="route('password.request')" class="block text-xs font-medium text-right text-orange-500 hover:text-orange-400">Password dimenticata?</Link>
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
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>