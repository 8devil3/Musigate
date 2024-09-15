<template>
    <BackofficeLayout as="div" :isLoading="formEmail.processing || formName.processing || formPassword.processing || formDeleteUser.processing" :onSuccess="formEmail.recentlySuccessful || formName.recentlySuccessful || formPassword.recentlySuccessful" :onFail="formEmail.hasErrors || formName.hasErrors || formPassword.hasErrors" title="Account" icon="fa-solid fa-user-gear">
        <template #title>
            Account
        </template>

        <template #content>
            <!-- avatar -->
            <FormElement>
                <template #title>
                    Avatar
                </template>

                <template #description>
                    L'immagine del tuo profilo. Verrà mostrata nella pagina del tuo Studio.<br><br>
                    Formati accettati: <strong> jpg, jpeg, png, bmp</strong><br>
                    Dimensione max: <strong>1 MB</strong><br>
                </template>

                <template #content>
                    <ImageUploader
                        v-model="props.avatar"
                        id="avatar-uploader"
                        :routeUpload="route('account.avatar_upload')"
                        :routeDelete="route('account.avatar_delete')"
                        :maxSizeMB="1"
                        accept="image/jpg, image/jpeg, image/png, image/bmp"
                    />
                </template>
            </FormElement>
            <!-- / -->

            <!-- nome e cognome -->
            <FormElement>
                <template #title>
                    Nome e cognome
                </template>

                <template #description>
                    Il nome e cognome del titolare dello Studio.<br>
                    Al salvataggio il cognome verrà reso puntato (es: "Mario Rossi" diventa "Mario R.").
                </template>

                <template #content>
                    <div class="space-y-4">
                        <Input id="edit-account-first-name" v-model="formName.first_name" placeholder="Nome del titolare" :error="formName.errors.first_name" class="w-full md:max-w-xs" required/>

                        <Input id="edit-account-last-name" v-model="formName.last_name" placeholder="Cognome del titolare" :error="formName.errors.last_name" class="w-full md:max-w-xs" required/>

                        <SaveButton @click="updateName()" />
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- email -->
            <FormElement>
                <template #title>
                    Email
                </template>

                <template #description>
                    La tua email inserita in fase di registrazione.
                </template>

                <template #content>
                    <div class="mb-4 space-y-4">
                        <Input id="edit-account-email" v-model="formEmail.email" placeholder="Email" :error="formEmail.errors.email" class="w-full md:max-w-xs" required/>
                        
                        <SaveButton @click="updateEmail()" />
                    </div>


                    <div v-if="props.mustVerifyEmail && $page.props.auth.user.email_verified_at === null" class="space-y-2">
                        <p class="text-sm text-red-500">
                            Indirizzo email non verificato
                        </p>

                        <p v-show="props.status === 'verification-link-sent'" class="text-sm">
                            Ti abbiamo inviato un messaggio di verifica al tuo indirizzo email, controlla anche nella casella "spam".
                        </p>
                        
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="text-sm text-orange-500 underline transition-colors rounded-md hover:text-orange-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                        >
                            Click qui per inviare nuovamente il messaggio di verifica
                        </Link>
                    </div>
                </template>
            </FormElement>
            <!-- / -->

            <!-- password -->
            <FormElement>
                <template #title>
                    Password
                </template>

                <template #description>
                    Utilizza una password lunga (minimo 8 caratteri) che contenga caratteri alfanumerici.
                </template>

                <template #content>
                    <form @submit.prevent="updatePassword()" class="space-y-4">
                        <Input type="password" id="edit-account-current-password" ref="currentPasswordInput" v-model="formPassword.current_password" placeholder="Password attuale" :error="formPassword.errors.current_password" class="w-full md:max-w-xs" required/>

                        <Input type="password" id="edit-account-new-password" v-model="formPassword.password" placeholder="Nuova password" :error="formPassword.errors.password" class="w-full md:max-w-xs" required/>

                        <Input type="password" id="edit-account-confirm-password" ref="passwordInput" v-model="formPassword.password_confirmation" placeholder="Conferma nuova passoword" :error="formPassword.errors.password_confirmation" class="w-full md:max-w-xs" required/>

                        <SaveButton />
                    </form>
                </template>
            </FormElement>
            <!-- / -->

            <!-- eliminazione account -->
            <FormElement>
                <template #title>
                    Elimina account
                </template>
                <template #description>
                    Elimina definitivamente il tuo account da Musigate.
                </template>
                <template #content>
                    <Button @click="openModalDeleteUser = true" icon="fa-solid fa-user-slash" text="Elimina account" color="red" />
                </template>
            </FormElement>
            <!-- / -->
        </template>
    </BackofficeLayout>

    <ModalDanger :isOpen="openModalDeleteUser" @close="closeModalDeleteUser()">
        <template #title>
            Eliminazione del tuo account
        </template>
        <template #description>
            <span class="font-semibold text-red-500">Attenzione, stai per eliminare definitivamente il tuo account.</span><br>
            Questa azione è irreversibile ed eliminerà tutti i dati associati a questa utenza.
            <form @submit.prevent="deleteUser()" class="flex flex-col gap-2 mt-2">
                Se sei sicuro, inserisci la tua password per procedere.
                <Input type="password" v-model="formDeleteUser.password" id="password" placeholder="Password" :error="formDeleteUser.errors.password" required />

                <div class="mt-4 space-x-2">
                    <Button type="submit" text="Elimina l'account" color="red" />
                    <Button @click="closeModalDeleteUser()" text="Annulla" color="gray" />
                </div>
            </form>
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ImageUploader from '@/Components/Backoffice/ImageUploader.vue';
import ModalDanger from '@/Components/ModalDanger.vue';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';

const props = defineProps({
    email: String,
    first_name: String,
    last_name: String,
    avatar: String,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

//email
const formEmail = useForm({
    email: props.email,
});

const updateEmail = () => {
    formEmail.patch(route('account.update'));
}


//nome e cognome
const formName = useForm({
    first_name: props.first_name,
    last_name: props.last_name,
});

const updateName = () => {
    formName.patch(route('account.update'));
}

//password
const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const formPassword = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

const updatePassword = () => {
    formPassword.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => formPassword.reset(),
        onError: () => {
            if (formPassword.errors.password) {
                formPassword.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (formPassword.errors.current_password) {
                formPassword.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};


//elimina utente
const openModalDeleteUser = ref(false);

const formDeleteUser = useForm({
    password: null,
});

const closeModalDeleteUser = ()=>{
    openModalDeleteUser.value = false
    formDeleteUser.reset();
}

const deleteUser = () => {
    formDeleteUser.delete(route('account.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            openModalDeleteUser.value = false;
        },
        onFinish: () => formDeleteUser.reset(),
    });
};

</script>
