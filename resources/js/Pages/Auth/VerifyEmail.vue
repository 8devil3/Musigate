<template>
    <AuthLayout @submitted="submit()" title="Verfica email" :isLoading="form.processing" :status="verificationLinkSent">
        <template #head>
            <h1 class="text-lg uppercase">Verifica email</h1>
            <h2 class="text-base font-normal text-center text-green-500">Grandioso!</h2>
            <p>
                Grazie di esserti registrato!<br>
                Per procedere verifica il tuo indirizzo email.
            </p>
        </template>

        <template #content>
            <Button type="submit" text="Reinvia il link di verifica email" :disabled="form.processing" :isLoading="form.processing" class="w-full" />
        </template>

        <template #actions>
            <Button @click="logout()" text="Esci" class="w-full" />
        </template>
    </AuthLayout>
</template>

<script setup>
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import Button from '@/Components/Form/Button.vue';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const logout = ()=>{
    router.post(route('logout'));
}

const verificationLinkSent = computed(()=>{
    if(props.status === 'verification-link-sent'){
        return "Ti abbiamo inviato il link di conferma all'indirizzo inserito in fase di registrazione.";
    }
});

</script>