<template>
    <ContentLayout
        @submitted="submit()"
        title="Descrizione"
        icon="fa-solid fa-file-lines"
    >
        <template #content>
            <!-- nome studio -->
            <FormElement>
                <template #title>
                    Nome
                </template>

                <template #description>
                    Inserisci il nome dello Studio.
                </template>

                <template #content>
                    <Input id="studio-name" v-model="form.name" placeholder="Nome dello Studio" :error="form.errors.name" required class="w-full lg:max-w-xs" />
                </template>
            </FormElement>
            <!-- / -->
            
            <!-- logo studio -->
            <FormElement>
                <template #title>
                    Logo
                </template>

                <template #description>
                    Carica il logo dello Studio.
                </template>

                <template #content>
                    <ImageUploader
                        v-model="props.studio.logo"
                        id="studio-logo-uploader"
                        routeUpload="studio.logo_upload"
                        routeDelete="studio.logo_delete"
                        :maxSizeMB="1"
                        accept="image/jpg, image/jpeg, image/png"
                    />
                </template>
            </FormElement>
            <!-- / -->
                
            <!-- categoria -->
            <!-- <FormElement>
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
            </FormElement> -->
            <!-- / -->

            <!-- visibile? -->
            <FormElement>
                <template #title>
                    Visibilità
                </template>

                <template #description>
                    Scegli se rendere lo Studio visibile e ricercabile.
                </template>

                <template #content>
                    <Toggle
                        v-model="form.is_visible"
                        :label="form.is_visible ? 'Studio visibile e ricercabile' : 'Studio non visibile e non ricercabile'"
                        :disabled="!props.studio.is_complete"
                        class="mb-4"
                    />

                    <CheckStudioData :studio="props.studio" />
                </template>
            </FormElement>
            <!-- / -->

            <!-- vat -->
            <FormElement>
                <template #title>
                    Partita IVA
                </template>

                <template #description>
                    La partita IVA dello Studio.
                </template>

                <template #content>
                    <Input v-model="form.vat" inputmode="numeric" :error="form.errors.vat" placeholder="Partita IVA" pattern="[0-9]{11}" class="w-full lg:max-w-xs" required />
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
                    <Checkbox id="studio-record-label" v-model="form.is_record_label">
                        Etichetta discografica
                    </Checkbox>
                </template>
            </FormElement>
            <!-- / -->

            <!-- descrizione -->
            <FormElement>
                <template #title>
                    Presentazione
                </template>

                <template #description>
                    Inserisci una breve presentazione dello Studio di almeno 100 caratteri, spazi non compresi.
                </template>

                <template #content>
                    <Textarea v-model="form.description" placeholder="Presentazione dello Studio" :minlength="100" :error="form.errors.description" required />
                </template>
            </FormElement>
            <!-- / -->
        </template>
        
        <template v-if="form.isDirty && !form.processing" #actions>
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Input from '@/Components/Form/Input.vue';
// import Radio from '@/Components/Form/Radio.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Toggle from '@/Components/Form/Toggle.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import ImageUploader from '@/Components/Backoffice/ImageUploader.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import CheckStudioData from './Dashboard/CheckStudioData.vue';

const props = defineProps({
    studio: Object,
});

const form = useForm({
    name: props.studio.name ?? null,
    vat: props.studio.vat ?? null,
    // category: props.studio.category ?? '',
    is_record_label: props.studio.is_record_label,
    is_visible: props.studio.is_visible,
    description: props.studio.description ?? null,
});

const submit = () => {
    form.put(route('studio.description.update'));
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
