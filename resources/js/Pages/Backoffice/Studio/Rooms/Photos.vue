<template>
    <ContentLayout
        as="div"
        title="Foto"
        icon="fa-solid fa-image"
        :isLoading="form.processing || formFeature.processing"
        :onSuccess="form.recentlySuccessful"
        :onFail="form.hasErrors"
        :backRoute="route('studio.links')"
        :tabLinks="tabLinks"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica le foto della Sala.<br>
                    Puoi impostare un'immagine in evidenza cliccando sulla stellina sopra le foto. L'immagine in evidenza è contornata in giallo e viene mostrata nella ricerca e come prima immagine nella galleria.<br>
                    Le foto devono essere in alta risoluzione, minimo <strong>1920 x 1080 px</strong>.
                    <br>
                    <br>
                    <strong>Massimo 6 foto.</strong><br>
                    Formati accettati: <strong>jpg, jpeg, png, bmp</strong><br>
                    Dimensione di ogni foto: <strong>max 6 MB</strong><br>
                    Risoluzione: <strong>minimo 1920 x 1080 px</strong><br>
                </template>

                <template #content>
                    <!-- errori di validazione -->
                    <div v-if="Object.entries(form.errors).length" class="p-4 mb-8 space-y-4 text-red-500 border border-red-500 rounded-xl">
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
                    </div>

                    <div v-if="props.photos.length" class="grid grid-cols-2 gap-2 mb-6 sm:grid-cols-2">
                        <div v-for="photo, indx in props.photos.sort(photo => photo.is_featured ? -1 : 1)" class="relative rounded-md overflow-clip group aspect-video" :class="photo.is_featured || indx === 0 ? 'border-4 p-1 shadow-md shadow-amber-500/50 border-amber-500' : 'border border-gray-600'">
                            <img :src="'/storage/' + photo.path" class="object-cover w-full h-full" />

                            <input type="checkbox" v-model="form.checkedPhotos" :value="photo.id" title="Seleziona per eliminare" class="absolute top-2 right-2 w-4 h-4 rounded-[4px] border-2 border-red-600 bg-gray-900 text-red-600 cursor-pointer shadow-sm" />

                            <button type="button" title="Imposta in evidenza" @click="featurePhoto(photo.id)" class="absolute flex items-center justify-center w-5 h-5 leading-none transition-opacity rounded-full bg-gray-900/30 hover:bg-gray-900/40 hover:text-amber-400 lg:opacity-0 left-2 top-2 text-amber-500 group-hover:opacity-100">
                                <i class="text-xs fa-solid fa-star"></i>
                            </button>
                        </div>
                    </div>

                    <Empty v-else>
                        <template #title>
                            Nessuna foto caricata.
                        </template>
                        <template #description>
                            Non sono presenti foto del tuo Studio.
                        </template>
                        <template #actions>
                            <Button @click="inputFile.click()" :isLoading="form.processing" text="Carica foto" icon="fa-solid fa-upload" />
                        </template>
                    </Empty>
                                        
                    <input @change="uploadPhotos($event.target.files)" type="file" ref="inputFile" accept="image/png, image/jpeg, image/bmp" multiple hidden />
                </template>
            </FormElement>
        </template>

        <template #actions>
            <div class="space-x-2">
                <Button v-if="form.checkedPhotos.length" @click="openModalDanger = true" text="Elimina foto selezionate" icon="fa-solid fa-trash-can" color="red" />
                <Button v-if="!form.checkedPhotos.length" @click="inputFile.click()" :isLoading="form.processing" text="Carica foto" icon="fa-solid fa-upload" color="green" />
            </div>
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
import Input from '@/Components/Form/Input.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import ModalDanger from '@/Components/ModalDanger.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    room: Object,
    photos: Array,
});

const inputFile = ref(null);
const openModalDanger = ref(false);
const currentPhotoId = ref(null);

const form = useForm({
    _method: 'PUT',
    photos: [],
    checkedPhotos: []
});

const uploadPhotos = (photos)=>{
    form.reset();

    if(photos){
        Object.values(photos).forEach((photo) => {
            form.photos.push(photo);
        });
        
        form.post(route('rooms.photos.update', props.room.id), {
            preserveScroll: true,
            onSuccess: ()=>{
                form.reset();
            }
        });
    }
}

const deletePhoto = ()=>{
    form.delete(route('rooms.photos.delete', props.room.id), {
        preserveScroll: true,
        onSuccess: ()=>{
            form.reset();
            openModalDanger.value = false;
        }
    });
}


const formFeature = useForm({});

const featurePhoto = (photoId)=>{
    formFeature.put(route('rooms.photos.featured', {room: props.room.id, photo: photoId}), {
        preserveScroll: true,
    });
}

const tabLinks = [
    {
        name: 'Descrizione',
        href: route('rooms.description.edit', props.room.id),
        active: route().current('rooms.description.edit', props.room.id)
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

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Foto',
    }, {default: () => page}),
};
</script>
