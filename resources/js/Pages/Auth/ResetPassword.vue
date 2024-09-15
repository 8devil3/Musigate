<template>
    <AuthLayout @submitted="submit()" title="Reimposta password" :isLoading="form.processing" :status="props.status">
        <template #head>
            <h1 class="text-lg">
                Reimposta la password.
            </h1>
        </template>

        <template #content>
            <Input type="email" id="reset-email" label="Email" placeholder="La tua email" v-model.trim="form.email" :error="form.errors.email" required autofocus />
                        
            <Input type="password" id="reset-password" label="Nuova password" placeholder="La nuova password" v-model="form.password" :error="form.errors.password" required />

            <Input type="password" id="reset-password-confirmation" label="Conferma password" placeholder="Conferma password" v-model="form.password_confirmation" :error="form.errors.password_confirmation" required />
        </template>

        <template #actions>
            <Button type="submit" text="Invia" :disabled="form.processing" :isLoading="form.processing" class="w-full" />
        </template>
    </AuthLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: null,
    password_confirmation: null,
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>