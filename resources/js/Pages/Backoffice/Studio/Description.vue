<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        title="Descrizione"
        icon="fa-solid fa-file-lines"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <!-- nome studio -->
            <FormElement required>
                <template #title>
                    Nome
                </template>

                <template #description>
                    Inserisci il nome dello Studio.
                </template>

                <template #content>
                    <Input id="studio-name" v-model="form.name" :error="form.errors.name" class="w-full lg:max-w-xs" required />
                </template>
            </FormElement>
            <!-- / -->
            
            <!-- logo studio -->
            <FormElement>
                <template #title>
                    Logo
                </template>

                <template #description>
                    Inserisci il logo dello Studio. Per un risultato ottimale è consigliabile un'immagine con proporzioni quadrate.
                </template>

                <template #content>
                    <ImageUploader
                        v-model="props.studio.logo"
                        id="studio-logo-uploader"
                        routeUpload="studio.general.logo_upload"
                        routeDelete="studio.general.logo_delete"
                        :maxSizeMB="1"
                        accept="image/jpg, image/jpeg, image/png, image/bmp"
                        rounded
                    />
                </template>
            </FormElement>
            <!-- / -->

            <!-- visibilità studio -->
            <FormElement>
                <template #title>
                    Visibilità
                </template>

                <template #description>
                    Modfica la visibilità dello Studio.<br>
                    Se il controllo viene disabilitato, lo Studio non sarà più ricercabile o visibile.
                </template>

                <template #content>
                    <div class="flex items-center gap-2">
                        <Toggle v-model="form.is_visible" :disabled="!props.studio.is_complete" />
                        <div v-if="form.is_visible" class="text-green-500">
                            Lo Studio è visibile e ricercabile.
                        </div>
                        <div v-else class="text-red-500">
                            Lo Studio non è visibile nè ricercabile.
                        </div>
                    </div>
                    <p v-if="!props.studio.is_complete" class="mt-4 text-gray-300">
                        Non sarà possibile modificare questa impostazione fino a quando non saranno salvate le seguenti informazioni:
                        <ul class="mt-2 list-none list-inside">
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.name" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                il nome dello Studio
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.category" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                la categoria
                            </li>
                            <li v-if="props.studio.category === 'Professional'">
                                <i v-if="props.studio.vat" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                la partita iva
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.desc?.length > 100" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                la presentazione di almeno 100 caratteri, spazi esclusi
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.location.complete_address" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                la <Link :href="route('studio.location.edit')" class="text-orange-500 underline transition-colors hover:text-orange-400">location</Link>
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.payment_methods?.length" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                almeno un <Link :href="route('studio.payment_methods.edit')" class="text-orange-500 underline transition-colors hover:text-orange-400">metodo di pagamento</Link>
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.photos?.length" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                almeno una <Link :href="route('studio.photos.edit')" class="text-orange-500 underline transition-colors hover:text-orange-400">foto</Link>
                            </li>
                            <li class="flex items-start gap-2">
                                <i v-if="props.studio.contacts?.email || props.studio.contacts?.phone || props.studio.contacts?.telegram || props.studio.contacts?.messenger || props.studio.contacts?.whatsapp" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check"></i>
                                <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark"></i>
                                almeno un <Link :href="route('studio.contacts.edit')" class="text-orange-500 underline transition-colors hover:text-orange-400">contatto</Link>
                            </li>
                        </ul>
                    </p>
                </template>
            </FormElement>
            <!-- / -->
                
            <!-- categoria -->
            <FormElement required>
                <template #title>
                    Categoria
                </template>

                <template #description>
                    Seleziona la categoria dello Studio.
                </template>

                <template #content>
                    <div class="flex items-center gap-8">
                        <Radio v-model="form.category" id="studio-category-professional" name="studio-category" value="Professional">Professional</Radio>
                        <Radio v-model="form.category" id="studio-category-home" name="studio-category" value="Home">Home</Radio>
                    </div>
                </template>
            </FormElement>

            <!-- vat -->
            <FormElement v-if="form.category === 'Professional'" required>
                <template #title>
                    Partita IVA
                </template>

                <template #description>
                    La partita IVA dello Studio.
                </template>

                <template #content>
                    <Input v-model="form.vat" :error="form.errors.vat" placeholder="Partita IVA" pattern="[0-9]{11}" class="w-full lg:max-w-xs" required />
                </template>
            </FormElement>
            <!-- / -->
                
            <!-- etichetta discografica -->
            <FormElement>
                <template #title>
                    Etichetta discografica
                </template>

                <template #description>
                    Spunta la casella se lo Studio è anche un'etichetta discografica.
                </template>

                <template #content>
                    <Checkbox id="studio-record-label" v-model="form.record_label">
                        Etichetta discografica
                    </Checkbox>
                </template>
            </FormElement>
            <!-- / -->

            <!-- descrizione -->
            <FormElement required>
                <template #title>
                    Presentazione
                </template>

                <template #description>
                    Inserisci una breve presentazione dello Studio di almeno 100 caratteri, spazi non compresi.
                </template>

                <template #content>
                    <Textarea v-model="form.desc" placeholder="Presentazione dello Studio" :minlength="100" :error="form.errors.desc" required />
                </template>
            </FormElement>
            <!-- / -->
        </template>
        
        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Radio from '@/Components/Form/Radio.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import ImageUploader from '@/Components/Backoffice/ImageUploader.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    studio: Object,
});

const form = useForm({
    name: props.studio.name,
    vat: props.studio.vat,
    category: props.studio.category,
    record_label: props.studio.record_label,
    desc: props.studio.desc,
    is_visible: props.studio.is_visible
});

const submit = () => {
    form.put(route('studio.general.update'), {
        preserveScroll: true,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Descrizione',
    }, {default: () => page}),
};
</script>
