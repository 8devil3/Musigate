<template>
    <AuthLayout @submitted="submit()" title="Reset password" :isLoading="form.processing" :status="props.status">
        <template #head>
            <h1 class="text-lg uppercase">Reset password</h1>
            <p>
                Inserisci il tuo indirizzo email per reimpostare la password.
            </p>
        </template>

        <template #content>
            <Input type="email" label="Email" placeholder="La tua email" autocomplete="on" v-model.trim="form.email" :error="form.errors.email" required />
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
    status: {
        type: String,
    },
    email: String,
});

const form = useForm({
    email: props.email ?? null,
});

const submit = () => {
    form.post(route('password.email'));
};
</script>