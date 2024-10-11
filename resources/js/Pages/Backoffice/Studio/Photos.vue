<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Foto"
        icon="fa-solid fa-image"
        :backRoute="route('studio.links')"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica le foto dello Studio e trascinale per riordinarle.<br>
                    <br>
                    Max {{ maxPhotos }} foto.<br>
                    Formati accettati: jpg, jpeg, png<br>
                    Dimensione massima cad.: 2 MB<br>
                    Risoluzione minima: 1280 x 720 px
                </template>

                <template #content>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <!-- foto -->
                        <draggable 
                            v-model="form.photos"
                            tag="div"
                            item-key="sort_index"
                            class="contents"
                        >
                            <template #item="{ element, index }">
                                <div>
                                    <div class="relative cursor-move">
                                        <img :src="element.id ? '/storage/' + element.path : element.path " alt="photo" class="object-cover w-full border rounded-xl aspect-video border-slate-800" />
                                        <button type="button" @click="deletePhoto(index, element.id)" title="Elimina foto" class="absolute flex items-center justify-center size-5 text-xs text-white bg-red-500 border border-white rounded-full shadow top-1 right-1 lg:top-2 lg:right-2">
                                            <i class="fa-solid fa-xmark" />
                                        </button>
        
                                        <div v-if="index === 0" class="absolute bottom-1 right-1 lg:bottom-2 lg:right-2 font-medium py-1 leading-none px-2 shadow-md bg-slate-900/70 border border-orange-500 rounded-full text-[10px] lg:text-xs text-white uppercase">
                                            principale
                                        </div>
                                    </div>

                                    <div v-if="form.errors['photos.' + index + '.file']" class="px-2 mt-1 text-xs font-normal text-red-500">
                                        {{ form.errors['photos.' + index + '.file'] }}
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
                                        Click o tap per caricare le foto<br>
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

        <template #actions>
            <SaveButton :isLoading="form.processing" :disabled="form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    photos: Object,
});

const form = useForm({
    photos: props.photos ?? [],
});

const maxPhotos = 6;

const previewPhotos = (files)=>{
    let photos = Array.from(files);

    if(photos.length > maxPhotos - form.photos.length){
        photos.splice(maxPhotos - form.photos.length);
    }

    photos.forEach((photo, index) => {
        form.photos.push({id: false, file: photo, path: URL.createObjectURL(photo), sort_index: index});
    });
};

const deletePhoto = (index, id)=>{
    if(!id){
        form.photos.splice(index, 1);
    } else {
        form.delete(route('studio.photos.delete', id), {
            preserveState: false,
        });
    }
};

const submit = ()=>{
    form.post(route('studio.photos.update'), {
        preserveState: false,
    });
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
