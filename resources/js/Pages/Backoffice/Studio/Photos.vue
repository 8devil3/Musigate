<template>
    <ContentLayout
        @submitted="submit()"
        title="Foto"
        icon="fa-solid fa-image"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica le foto di presentazione dello Studio, le foto delle Sale potrai caricarle quando inserirai le Sale. Trascinale per riordinarle.
                    <br><br>
                    Max <strong>{{ maxPhotos }} foto</strong>.<br>
                    Formati accettati: <strong>jpg, jpeg, png, bmp</strong><br>
                    Dimensione massima di ogni foto: <strong>3 MB</strong>
                    
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
                    <div class="grid grid-cols-2 gap-4">
                        <!-- foto -->
                        <draggable 
                            v-model="form.photos"
                            tag="div"
                            item-key="sort_index"
                            class="contents"
                            handle=".photo"
+                        >
                            <template #item="{ element, index }">
                                <div class="relative cursor-move">
                                    <img :src="element.id ? '/storage/' + element.path : element.path " alt="photo" class="object-cover w-full border rounded-lg md:rounded-xl aspect-video border-slate-800 photo" />

                                    <Checkbox v-if="element.id" v-model="form.selected_photos" :value="element.id" :key="element.id" class="absolute z-40 top-2 left-2" />

                                    <button v-else type="button" @click="removePhoto(index)" title="Elimina foto" class="absolute z-40 flex items-center justify-center text-xs text-white bg-red-500 border border-white rounded-full shadow size-5 top-1 right-1 lg:top-2 lg:right-2">
                                        <i class="fa-solid fa-xmark" />
                                    </button>

                                    <div v-if="index === 0" class="absolute bottom-1 right-1 lg:bottom-2 lg:right-2 font-medium py-1 leading-none px-2 shadow-md bg-slate-900/70 border border-orange-500 rounded-full text-[10px] lg:text-xs text-white uppercase">
                                        principale
                                    </div>
                                </div>
                            </template>
                        </draggable>
                        <!-- / -->
                        
                        <!-- placeholder -->
                        <template v-if="form.photos.length < maxPhotos">
                            <label for="carica-foto2" class="relative flex items-center justify-center p-4 text-xs text-center transition-colors border-2 border-dashed cursor-pointer border-slate-400 rounded-xl text-slate-400 hover:bg-slate-800">
                                <div class="space-y-1">
                                    <i class="text-2xl fa-solid fa-upload" />
                                    <div>
                                        Click o tap per caricare le foto
                                    </div>
                                </div>
    
                                <input id="carica-foto2" type="file" @change="previewPhotos($event.target.files)" accept="image/png, image/jpeg" hidden multiple />
                            </label>
    
                            <div v-for="i in maxPhotos -1 - form.photos.length" class="flex items-center justify-center p-4 border aspect-video border-slate-600 rounded-xl text-slate-600">
                                <i class="text-2xl lg:text-4xl fa-solid fa-camera" />
                            </div>
                        </template>
                        <!-- / -->
                    </div>
                </template>
            </FormElement>
        </template>

        <template v-if="form.selected_photos.length || (form.isDirty && !form.selected_photos.length && !form.processing)" #actions>
            <Button v-if="form.isDirty && !form.selected_photos.length && !form.processing" @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <Button v-if="form.selected_photos.length" @click="deleteSelectedPhotos()" text="Elimina" icon="fa-solid fa-trash-can" color="red" />
            <SaveButton v-if="form.isDirty && !form.selected_photos.length && !form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    photos: Object,
});

const maxPhotos = 6;

const form = useForm({
    photos: props.photos ?? [],
    selected_photos: [],
});

const submit = ()=>{
    form.post(route('studio.photos.update'), {
        preserveState: false,
        onSuccess: ()=> form.selected_photos = [],
    });
};

const previewPhotos = (files)=>{
    let photos = Array.from(files);

    if(photos.length > maxPhotos - props.photos.length){
        photos.splice(maxPhotos - props.photos.length);
    }

    photos.forEach((photo, index) => {
        form.photos.push({
            id: null,
            file: photo,
            path: URL.createObjectURL(photo),
            sort_index: index
        });
    });
};

const removePhoto = (index)=>{
    form.photos.splice(index, 1);
};

const deleteSelectedPhotos = ()=>{
    if(form.selected_photos.length){
        form.delete(route('studio.photos.delete'), {
            preserveState: false,
            onSuccess: ()=> form.selected_photos = [],
        });
    }
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Foto',
    }, {default: () => page}),
};
</script>
