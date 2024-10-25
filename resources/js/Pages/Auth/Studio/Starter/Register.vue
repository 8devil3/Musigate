<template>
    <RegisterStudioLayout @submitted="submit()" title="Registrazione Studio" :isLoading="isLoading">
        <template #content>
            <!-- titolare -->
            <div v-if="props.step === 1" class="w-full max-w-xs mx-auto space-y-6">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                    1/3 - Nome e categoria
                </h2>

                <ul class="text-red-500">
                    <li v-for="error in usePage().props.errors">{{ error }}</li>
                </ul>

                <div class="space-y-4">
                    <Input v-model="formStep1.name" label="Nome Studio" placeholder="Il nome dello Studio" :error="formStep1.errors.name" required />
    
                    <fieldset class="flex gap-6 p-3 border border-slate-400 rounded-2xl">
                        <legend class="px-1 text-xs font-normal text-white">
                            Categoria dello Studio
                        </legend>
                        <Radio v-model="formStep1.category" name="register-studio-category" value="Professional" required>
                            Professional
                        </Radio>
                        <Radio v-model="formStep1.category" name="register-studio-category" value="Home">
                            Home
                        </Radio>
                    </fieldset>

                    <Input v-if="formStep1.category === 'Professional'" inputmode="numeric" v-model="formStep1.vat" pattern="[0-9]{11}" label="Partita IVA" placeholder="La partita IVA della tua attività" :error="formStep1.errors.vat" required />
                </div>

                <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :disabled="isLoading" class="w-full" />
            </div>
            <!-- / -->

            <!-- dati studio -->
            <div v-else-if="props.step === 2" class="w-full max-w-xs mx-auto space-y-6">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                    2/3 - Location
                </h2>

                <div class="space-y-4">
                    <!-- location -->                    
                    <div>
                        <GooglePlacesAutocomplete
                            v-if="!formStep2.is_manual_address"
                            v-model="formStep2.complete_address"
                            @error="formStep2.complete_address = null"
                            label="Indirizzo completo"
                            required
                        />

                        <div v-else class="grid grid-cols-3 gap-x-2 gap-y-4">
                            <Input v-model="formStep2.address" label="Indirizzo" placeholder="Indirizzo, senza numero civico" :error="formStep2.errors.address" required class="col-span-2" />

                            <Input v-model="formStep2.number" label="Civico" placeholder="Civico" :error="formStep2.errors.number" class="col-span-1" />

                            <Input v-model="formStep2.city" label="Città" placeholder="Città" :error="formStep2.errors.city" required class="col-span-2" />

                            <Input v-model="formStep2.cap" label="CAP" placeholder="CAP" pattern="[0-9]{5}" inputmode="numeric" :error="formStep2.errors.cap" :required="formStep2.is_manual_address" class="col-span-1" />

                            <Input v-model="formStep2.province" label="Provincia" placeholder="Provincia" :error="formStep2.errors.province" required class="col-span-full" />
                        </div>
                    </div>

                    <div class="px-4 mt-4">
                        <Checkbox v-model="formStep2.is_manual_address">Inserimento manuale indirizzo</Checkbox>
                    </div>
                    <!-- / -->
                </div>

                <div class="grid grid-cols-2 gap-12">
                    <Button @click="router.get(route('register.studio.starter.step_1', props.studio_data))" icon="fa-solid fa-arrow-left" :disabled="isLoading" text="Indietro" color="slate" />

                    <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :disabled="isLoading" />
                </div>
            </div>
            <!-- / -->

            <!-- account -->
            <div v-else-if="props.step === 3" class="w-full max-w-xs mx-auto space-y-6">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                    3/3 - Account
                </h2>

                <div class="space-y-4">
                    <GoogleLogin />

                    <Input v-model="formStep3.first_name" label="Nome" placeholder="Il tuo nome" :error="formStep3.errors.first_name" required  />

                    <Input v-model="formStep3.last_name" label="Cognome" placeholder="Il tuo cognome" :error="formStep3.errors.last_name" required />

                    <Input v-model="formStep3.email" type="email" label="Email" placeholder="La tua email" :error="formStep3.errors.email" required />

                    <Input v-model="formStep3.password" type="password" label="Password" placeholder="La tua password" :error="formStep3.errors.password" required />

                    <Input v-model="formStep3.password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="formStep3.errors.password_confirmation" required/>

                    <!-- tos e privacy -->
                    <div class="px-4 space-y-4">
                        <Checkbox v-model="formStep3.tos" id="register-tos" required>
                            Accetto i <Link :href="route('tos')" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Termini e Condizioni</Link>
                        </Checkbox>

                        <Checkbox v-model="formStep3.privacy" id="register-privacy" required>
                            Accetto la <Link :href="route('privacy')" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Privacy policy</Link>
                        </Checkbox>
                    </div>
                    <!-- / -->
                </div>

                <div class="grid grid-cols-2 gap-12">
                    <Button @click="router.get(route('register.studio.starter.step_2', props.studio_data))" icon="fa-solid fa-arrow-left" :disabled="isLoading" text="Indietro" color="slate" />

                    <Button text="Registrati" type="submit" icon="fa-solid fa-check" :disabled="isLoading" color="green" />
                </div>
            </div>
            <!-- / -->
        </template>
    </RegisterStudioLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import RegisterStudioLayout from '@/Layouts/Register/RegisterStudioLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Radio from '@/Components/Form/Radio.vue';
import Input from '@/Components/Form/Input.vue';
import GoogleLogin from '../../GoogleLogin.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import GooglePlacesAutocomplete from '@/Components/GooglePlacesAutocomplete.vue';

const props = defineProps({
    step: Number,
    studio_step1: Object,
    studio_step2: Object,
});

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';

const formStep1 = useForm({
    step: 1,
    name: props.studio_step1?.name ?? null,
    category: props.studio_step1?.category ?? null,
    vat: props.studio_step1?.vat ?? null,
});

const formStep2 = useForm({
    step: 2,
    complete_address: props.studio_step2?.complete_address ?? null,
    address: props.studio_step2?.address ?? null,
    number: props.studio_step2?.number ?? null,
    cap: props.studio_step2?.cap ?? null,
    city: props.studio_step2?.city ?? null,
    province: props.studio_step2?.province ?? null,
    is_manual_address: props.studio_step2?.is_manual_address ?? false,
});

const formStep3 = useForm({
    step: 3,
    first_name: null,
    last_name: null,
    email: null,
    password: null,
    password_confirmation: null,
    tos: false,
    privacy: false,
});

const isLoading = ref(false);

router.on('start', ()=> isLoading.value = true);
router.on('finish', ()=> isLoading.value = false);

const submit = () => {
    if(isLoading.value) return;

    if(props.step === 1) formStep1.get(route('register.studio.starter.step_2'));
    else if(props.step === 2) formStep2.get(route('register.studio.starter.step_3'));
    else if(props.step === 3) formStep3.post(route('register.studio.starter.store'), {
        onFinish: () => formStep3.reset('password', 'password_confirmation'),
    });
};

</script>