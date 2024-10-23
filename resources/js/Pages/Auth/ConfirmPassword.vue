<template>
    <AuthLayout @submitted="submit()" title="Conferma password" :isLoading="form.processing">
        <template #head>
            <h1 class="text-lg uppercase">
                Conferma la password immessa.
            </h1>
        </template>
        
        <template #content>
            <Input type="password" id="login-password" label="Conferma password" placeholder="Conferma password" v-model="form.password" :error="form.errors.password" required autofocus/>
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

const form = useForm({
    password: null,
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

