<template>
    <div class="space-y-4">
        <form @submit.prevent="uploadImage()" class="relative flex items-center gap-4">
            <button type="button" v-if="vModel" @click="deleteImage()" :disabled="form.processing" title="Elimina il logo" class="absolute flex items-center justify-center w-6 h-6 text-red-600 border border-red-600 rounded-full -top-2 -left-2 bg-red-600/10">
                <i class="text-sm fa-solid fa-xmark" />
            </button>

            <label :for="props.id" class="flex items-center justify-center w-32 h-32 text-center transition-opacity border-2 border-dashed rounded-full cursor-pointer border-slate-400 text-slate-400 hover:opacity-80 overflow-clip shrink-0">
                <div v-if="!vModel" class="space-y-1 text-center">
                    <i class="text-3xl fa-solid fa-camera" />
                    <div class="text-xs">
                        Click o tap per caricare
                    </div>
                </div>
    
                <img v-else :src="vModel.includes('http') ? vModel : '/storage/' + vModel" alt="img" class="object-cover w-full h-full rounded-full" />

                <input type="file" :id="props.id" @change="uploadImage($event.target.files[0])" :accept="props.accept" :disabled="form.processing" hidden />
            </label>

            <div class="space-y-2 text-xs">
                <div class="leading-relaxed text-left">
                    <div>
                        Formati accettati: <strong>{{ props.accept.replaceAll('image/', '').toUpperCase() }}</strong>
                    </div>
                    <div>
                        Max <strong>{{ props.maxSizeMB ?? 2 }} MB</strong>
                    </div>
                </div>

                <Spinner v-if="form.progress" class="w-6 h-6 orange"/>

                <div v-show="form.errors.file" class="text-sm font-normal text-left text-red-600">
                    {{ form.errors.file }}
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import ProgressBar from '@/Components/Form/ProgressBar.vue';
import Spinner from '../Spinner.vue';

const props = defineProps({
    routeUpload: String,
    routeDelete: String,
    rounded: Boolean,
    maxSizeMB: Number,
    accept: String,
    id: String,
    rounded: Boolean
});

const vModel = defineModel({default: null});

const form = useForm({
    file: null,
});

const uploadImage = (file)=>{
    form.file = file;

    form.post(route(props.routeUpload), {
        preserveScroll: true,
        onFinish: ()=>{
            form.reset();
        }
    });
}

const deleteImage = ()=>{
    form.delete(route(props.routeDelete), {
        preserveState: false,
        onFinish: ()=>{
            form.reset();
        }
    });
}


// const getPreview = (e) => {
//     form.file = e

//     let reader = new FileReader();
//     reader.readAsDataURL(e);
//     reader.onload = function () {
//         preview.value = reader.result
//     }
// }
</script>
