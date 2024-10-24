<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.bundle.name"
        icon="fa-solid fa-image"
        backRoute="pacchetti.index"
        :tabLinks="tabLinks"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica una foto di presentazione del pacchetto.<br><br>
                    Formati accettati: <strong>jpg, jpeg, png, bmp</strong><br>
                    Dimensione massima: <strong>2 MB</strong>

                    <div v-if="Object.keys(usePage().props.errors).length" class="mt-4 font-normal text-red-500">
                        Errori:
                        <ul class="text-xs list-none list-image-none">
                            <li v-for="error in usePage().props.errors" class="flex items-start gap-1.5 text-xs">
                                <i class="mt-0.5 fa-solid fa-xmark" />
                                <div>
                                    {{ error }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </template>

                <template #content>
                    <!-- foto -->
                    <div v-if="form.photo || preview" class="relative">
                        <img v-if="preview" :src="preview" alt="photo" class="object-cover w-full border rounded-lg md:rounded-xl aspect-video border-slate-800" />
                        <img v-else :src="'/storage/' + form.photo" alt="photo" class="object-cover w-full border rounded-lg md:rounded-xl aspect-video border-slate-800" />

                        <button type="button" @click="removePhoto()" title="Elimina foto" class="absolute z-40 flex items-center justify-center text-xs text-white bg-red-500 border border-white rounded-full shadow size-5 top-1 right-1 lg:top-2 lg:right-2">
                            <i class="fa-solid fa-xmark" />
                        </button>
                    </div>
                    <!-- / -->

                    <!-- placeholder -->
                    <label v-else for="carica-foto3" class="relative flex items-center justify-center w-full p-4 text-xs text-center transition-colors border-2 border-dashed cursor-pointer aspect-video border-slate-400 rounded-xl text-slate-400 hover:bg-slate-800">
                        <div class="space-y-1">
                            <i class="text-2xl fa-solid fa-upload" />
                            <div>
                                Click o tap per caricare le foto
                            </div>
                        </div>

                        <input id="carica-foto3" type="file" @change="previewPhoto($event.target.files[0])" accept="image/png, image/jpeg" hidden />
                    </label>
                    <!-- / -->
                </template>
            </FormElement>
        </template>

        <template v-if="form.isDirty && !form.processing" #actions>
            <Button @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';

const props = defineProps({
    bundle: Object,
    photo: String,
});

const form = useForm({
    photo: props.photo ?? null,
});

const preview = ref(null);

const submit = ()=>{
    form.post(route('pacchetti.photo.update', props.bundle.id),{
        preserveState: false,
        onSuccess: ()=> preview.value = null,
    });
};

const previewPhoto = (file)=>{
    preview.value = null;
    form.photo = file;
    preview.value = URL.createObjectURL(file);
};

const removePhoto = ()=>{
    if(preview.value) form.photo = null;
    else router.delete(route('pacchetti.photo.destroy', props.bundle.id), {preserveState: false});
    preview.value = null;
};

const tabLinks = computed(()=>{
    if(props.bundle.id){
        return [
            {
                label: 'Descrizione',
                route: 'pacchetti.edit',
                params: props.bundle.id,
            },
            {
                label: 'Tariffe',
                route: 'pacchetti.prices.edit',
                params: props.bundle.id,
            },
            {
                label: 'Foto',
                route: 'pacchetti.photo.edit',
                params: props.bundle.id,
            },
        ];
    } else {
        return null;
    }
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Foto',
    }, {default: () => page}),
};
</script>
