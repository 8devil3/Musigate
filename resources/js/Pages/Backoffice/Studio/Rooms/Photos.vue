<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.room.name"
        icon="fa-solid fa-image"
        :backRoute="route('sale-prova.index')"
        :tabLinks="tabLinks"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Carica le foto della Sala e trascinale per riordinarle.<br><br>
                    Max <strong>{{ maxPhotos }} foto</strong>.<br>
                    Formati accettati: <strong>jpg, jpeg, png, bmp</strong><br>
                    Dimensione massima di ogni foto: <strong>2 MB</strong>
                </template>

                <template #content>
                    <div class="grid grid-cols-2 gap-4">
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
                                        <Checkbox v-if="element.id" v-model="form.selected_photos" :value="element.id" class="absolute top-2 right-2" />

                                        <button v-else type="button" @click="removePhoto(index)" title="Elimina foto" class="absolute flex items-center justify-center text-xs text-white bg-red-500 border border-white rounded-full shadow size-5 top-1 right-1 lg:top-2 lg:right-2">
                                            <i class="fa-solid fa-xmark" />
                                        </button>

                                        <img :src="element.id ? '/storage/' + element.path : element.path " alt="photo" class="object-cover w-full border rounded-xl aspect-video border-slate-800" />

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

        <template #actions>
            <Button v-if="form.selected_photos.length" @click="deleteSelectedPhotos()" text="Elimina" icon="fa-solid fa-trash-can" color="red" />
            <SaveButton v-if="form.isDirty && !form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import draggable from 'vuedraggable';

const props = defineProps({
    room: Object,
    photos: Object,
});

const maxPhotos = 6;

const form = useForm({
    photos: props.photos ?? [],
    selected_photos: [],
});

const submit = ()=>{
    form.post(route('sale-prova.photos.update', props.room.id),{
        preserveState: false,
        onSuccess: ()=> form.selected_photos = [],
    });
};

const previewPhotos = (files)=>{
    let photos = Array.from(files);

    if(photos.length > maxPhotos - props.photos.length){
        photos.splice(maxPhotos - props.photos.length);
    }

    photos.forEach((photo, index)=>{
        form.photos.push({
            id: false,
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
        form.delete(route('sale-prova.photos.destroy', props.room.id), {
            preserveState: false,
            onSuccess: ()=> form.selected_photos = [],
        });
    }
};

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                label: 'Descrizione',
                route: 'sale-prova.edit',
                params: props.room.id,
            },
            {
                label: 'Tariffe',
                route: 'sale-prova.prices.edit',
                params: props.room.id,
            },
            {
                label: 'Equipaggiamento',
                route: 'sale-prova.equipment.edit',
                params: props.room.id,
            },
            {
                label: 'Foto',
                route: 'sale-prova.photos.edit',
                params: props.room.id,
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
