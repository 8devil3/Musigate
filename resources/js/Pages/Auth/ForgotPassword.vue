<template>
    <AuthLayout @submitted="submit()" title="Password dimenticata?" :isLoading="form.processing" :status="props.status">
        <template #head>
            <h1 class="text-lg">Password dimenticata?</h1>
            <p>
                Nessun problema, inserisci il tuo indirizzo email per reimpostarla.
            </p>
        </template>

        <template #content>
            <Input type="email" id="forgot-email" label="Email" placeholder="La tua email" v-model.trim="form.email" :error="form.errors.email" required />
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
});

const form = useForm({
    email: null,
});

const submit = () => {
    form.post(route('password.email'));
};
</script>