<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.room.name"
        icon="fa-solid fa-image"
        :isLoading="form.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        :backRoute="route('rooms.index')"
        showBackRoute
        :tabLinks="tabLinks"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica le foto della sala e trascinale per riordinarle.<br>
                    <br>
                    Max 6 foto.<br>
                    Formati accettati: jpg, jpeg, png<br>
                    Dimensione massima cad.: 2 MB<br>
                    Risoluzione minima: 1920 x 1080 px
                </template>

                <template #content>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <!-- foto -->
                        <div v-for="img, index in form.photos" class="relative">
                            <img :src="img.id ? '/storage/' + img.path : img.path " alt="photo" class="object-cover w-full border rounded-xl aspect-video border-slate-800" />
                            <button type="button" @click="deletePhoto(index, img.id)" title="Elimina foto" class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-900 border border-red-600 rounded-full shadow top-1 right-1 lg:top-2 lg:right-2">
                                <i class="fa-solid fa-xmark" />
                            </button>

                            <div v-if="index === 0" class="absolute bottom-1 right-1 lg:bottom-2 lg:right-2 font-medium py-1 leading-none px-2 shadow-md bg-slate-900/70 border border-orange-500 rounded-full text-[10px] text-white uppercase">
                                principale
                            </div>
                        </div>
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


                    <!-- errori di validazione -->
                    <!-- <div v-if="Object.entries(form.errors).length" class="p-4 mb-8 space-y-4 text-red-500 border border-red-500 rounded-xl">
                        <h4>Errori di caricamento foto</h4>
                        <ul class="ml-4 space-y-1 list-disc list-outside">
                            <li v-for="error, key in form.errors">
                                <template v-if="error.includes(key)">
                                    {{ error.replace(key, form.photos[key.replace('photos.', '')].name) }}
                                </template>
                                <template v-else>
                                    {{ error }}
                                </template>
                            </li>
                        </ul>
                    </div> -->
                </template>
            </FormElement>
        </template>

        <template #actions>
            <SaveButton :isLoading="form.processing" :disabled="form.processing" />
        </template>
    </ContentLayout>
    
    <ModalDanger :isOpen="openModalDanger" @close="openModalDanger = false; currentPhotoId = null">
        <template #title>
            Elimina foto selezionate
        </template>
        <template #description>
            Confermi l'eliminazione delle foto selezionate?<br>
            Questa azione è irreversibile.
        </template>
        <template #actions>
            <Button text="Sì, elimina" @click="deletePhoto()" :isLoading="form.processing" :disabled="form.processing" color="red"/>
            <Button text="No, annulla" color="gray" @click="openModalDanger = false; currentPhotoId = null"/>
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Button from '@/Components/Form/Button.vue';
import ModalDanger from '@/Components/ModalDanger.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    room: Object,
    photos: Object,
});

const openModalDanger = ref(false);
const currentPhotoId = ref(null);

const form = useForm({
    photos: props.photos ?? [],
});

const maxPhotos = 6;

const previewPhotos = (files)=>{
    let photos = Array.from(files);

    if(photos.length > maxPhotos - form.photos.length){
        photos.splice(maxPhotos - form.photos.length);
    }

    photos.forEach(photo => {        
        form.photos.push({id: false, file: photo, path: URL.createObjectURL(photo)});
    });
};

const deletePhoto = (index, id)=>{
    if(!id){
        form.photos.splice(index, 1);
    } else {
        form.delete(route('rooms.photos.delete', [id, props.room.id]), {
            preserveState: false,
        });
    }
};

const submit = ()=>{
    form.post(route('rooms.photos.update', props.room.id));
};

const tabLinks = [
    {
        name: 'Descrizione',
        href: route('rooms.edit', props.room.id),
        active: route().current('rooms.edit', props.room.id)
    },
    {
        name: 'Equipaggiamento',
        href: route('rooms.equipment.edit', props.room.id),
        active: route().current('rooms.equipment.edit', props.room.id)
    },
    {
        name: 'Foto',
        href: route('rooms.photos.edit', props.room.id),
        active: route().current('rooms.photos.edit', props.room.id)
    },
];

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Foto',
    }, {default: () => page}),
};
</script>
