<template>
    <RegisterStudioLayout @submitted="submit()" title="Registrazione Studio" :isLoading="isLoading">
        <template #title>
            1/3 - Rappresentante legale
        </template>

        <template #content>
            <!-- titolare -->
            <div v-if="props.step === 1" class="w-full max-w-xs mx-auto space-y-6">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                    1/3 - Rappresentante legale
                </h2>

                <div class="space-y-4">
                    <Input v-model="formStep1.first_name" label="Nome" placeholder="Il nome del titolare dello Studio" :error="formStep1.errors.first_name" required  />

                    <Input v-model="formStep1.last_name" label="Cognome" placeholder="Il cognome del titolare dello Studio" :error="formStep1.errors.last_name" required />

                    <!-- <Input type="date" v-model="formStep1.dob" label="Data di nascita" placeholder="La data di nascita" :error="formStep1.errors.dob" required />

                    <Input v-model="formStep1.cod_fiscale" pattern="[a-z][0-9]{16}" label="Codice fiscale" placeholder="Il codice fiscale" :error="formStep1.errors.cod_fiscale" required /> -->
                </div>

                <Button text="Prosegui" type="submit" icon="fa-solid fa-arrow-right" iconRight :disabled="isLoading" class="w-full" />
            </div>
            <!-- / -->

            <!-- dati studio -->
            <div v-else-if="props.step === 2" class="w-full max-w-xs mx-auto space-y-6">
                <h2 class="pb-1 m-0 text-base text-center border-b border-orange-500">
                    2/3 - Dati Studio
                </h2>

                <div class="space-y-4">
                    <Input v-model="formStep2.name" label="Nome Studio" placeholder="Il nome dello Studio" :error="formStep2.errors.name" required />
    
                    <!-- <fieldset class="px-2 py-1 border border-slate-400 rounded-xl">
                        <legend class="px-1 text-xs font-normal text-slate-300">
                            Seleziona la categoria dello Studio
                        </legend>

                        <div class="grid grid-cols-2 gap-4 p-2">
                            <Radio v-model="formStep2.category" name="register-studio-category" value="Professional" required>
                                Professional
                            </Radio>
                            <Radio v-model="formStep2.category" name="register-studio-category" value="Home">
                                Home
                            </Radio>
                        </div>
                    </fieldset> -->

                    <Input inputmode="numeric" v-model="formStep2.vat" pattern="[0-9]{11}" label="Partita IVA" placeholder="La partita IVA della tua attività" :error="formStep2.errors.vat" required />

                    <!-- location -->                    
                    <div class="grid grid-cols-3 gap-x-2 gap-y-4">
                        <GooglePlacesAutocomplete
                            v-model="formStep2.complete_address"
                            @addressData="setFormAddress"
                            required
                            class="col-span-full"
                            label="Indirizzo completo"
                        />
                        <Input v-model="formStep2.address" label="Indirizzo" placeholder="Indirizzo, senza numero civico" :error="formStep2.errors.address" :disabled="!isManualAddress" required class="col-span-2" />

                        <Input v-model="formStep2.number" label="Civico" placeholder="Civico" :error="formStep2.errors.number" :disabled="!isManualAddress" class="col-span-1" />

                        <Input v-model="formStep2.city" label="Città" placeholder="Città" :error="formStep2.errors.city" :disabled="!isManualAddress" required class="col-span-2" />

                        <Input v-model="formStep2.cap" label="CAP" placeholder="CAP" pattern="[0-9]{5}" :error="formStep2.errors.cap" :disabled="!isManualAddress" :required="isManualAddress" class="col-span-1" />

                        <Input v-model="formStep2.province" label="Provincia" placeholder="Provincia" :error="formStep2.errors.province" :disabled="!isManualAddress" required class="col-span-full" />
                    </div>
                    
                    <div class="px-4 mt-4">
                        <Checkbox v-model="isManualAddress">Inserimento manuale indirizzo</Checkbox>
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
    
                    <Input v-model="formStep3.email" type="email" label="Email" placeholder="La tua email" :error="formStep3.errors.email" required />
    
                    <Input v-model="formStep3.password" type="password" label="Password" placeholder="La tua password" :error="formStep3.errors.password" required />
    
                    <Input v-model="formStep3.password_confirmation" type="password" label="Conferma password" placeholder="Conferma la password" :error="formStep3.errors.password_confirmation" required/>
    
                    <!-- tos e privacy -->
                    <div class="px-4 space-y-4">
                        <Checkbox v-model="formStep3.tos" id="register-tos" required>
                            Accetto i <Link :href="tosLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Termini e Condizioni</Link>
                        </Checkbox>
    
                        <Checkbox v-model="formStep3.privacy" id="register-privacy" required>
                            Accetto la <Link :href="privacyLink" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Privacy policy</Link>
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
import { computed, ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import RegisterStudioLayout from '@/Layouts/Register/RegisterStudioLayout.vue';
import Button from '@/Components/Form/Button.vue';
import Radio from '@/Components/Form/Radio.vue';
import Input from '@/Components/Form/Input.vue';
import GoogleLogin from '../../GoogleLogin.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import GooglePlacesAutocomplete from '@/Components/GooglePlacesAutocomplete.vue';

const props = defineProps({
    step: Number,
    data_step1: Object,
    data_step2: Object,
});

const privacyLink = import.meta.env.VITE_PRIVACY_LINK ?? '#';
const tosLink = import.meta.env.VITE_TOS_LINK ?? '#';
const isManualAddress = ref(false);

const formStep1 = useForm({
    step: 1,
    first_name: props.data_step1?.first_name ?? null,
    last_name: props.data_step1?.last_name ?? null,
});

const formStep2 = useForm({
    step: 2,
    name: props.data_step2?.studio_name ?? null,
    // category: props.data_step2?.category ?? null,
    vat: props.data_step2?.vat ?? null,

    complete_address: props.data_step2?.complete_address ?? null,
    address: props.data_step2?.address ?? null,
    number: props.data_step2?.number ?? null,
    cap: props.data_step2?.cap ?? null,
    city: props.data_step2?.city ?? null,
    province: props.data_step2?.province ?? null,
});

const formStep3 = useForm({
    step: 3,
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

const setFormAddress = (e)=>{
    formStep2.address = e.address;
    formStep2.number = e.number;
    formStep2.cap = e.cap;
    formStep2.city = e.city;
    formStep2.province = e.province;
};

</script>