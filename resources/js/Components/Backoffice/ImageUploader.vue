<template>
    <div class="space-y-4">
        <form @submit.prevent="uploadImage()" class="relative flex items-center gap-4">
            <button type="button" v-if="vModel" @click="deleteImage()" :disabled="isLoading" title="Elimina il logo" class="absolute flex items-center justify-center w-6 h-6 text-red-600 border border-red-600 rounded-full -top-2 -left-2 bg-red-600/10">
                <i class="text-sm fa-solid fa-xmark" />
            </button>

            <label :for="props.id" class="flex items-center justify-center w-32 h-32 text-center transition-opacity border-2 border-dashed rounded-full cursor-pointer border-slate-300 hover:opacity-80 overflow-clip shrink-0">
                <i v-if="!vModel" class="text-4xl text-slate-300 fa-solid fa-camera" />
    
                <img v-else :src="vModel.includes('http') ? vModel : '/storage/' + vModel" alt="img" class="object-contain w-full h-full rounded-full" />

                <input type="file" :id="props.id" @change="uploadImage($event.target.files[0])" :accept="props.accept" :disabled="isLoading" hidden />
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

                <ProgressBar :progress="progress" />
            </div>
        </form>
                     
        <div v-show="form.errors.file" class="text-sm text-left text-red-600">{{ form.errors.file }}</div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ProgressBar from '@/Components/Form/ProgressBar.vue';

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

const progress = ref(0);
const isLoading = ref(false);

const form = useForm({
    _method: 'PUT',
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
    router.delete(route(props.routeDelete), {
        preserveScroll: true,
        onFinish: ()=>{
            form.reset();
        }
    });
}

router.on('start', ()=> isLoading.value = true);

router.on('progress', (e)=>{
    if(e.detail.progress.percentage) progress.value = Math.round(e.detail.progress.percentage);
});

router.on('finish', ()=> {
    isLoading.value = false;
    progress.value = 0;
});

// const getPreview = (e) => {
//     form.file = e

//     let reader = new FileReader();
//     reader.readAsDataURL(e);
//     reader.onload = function () {
//         preview.value = reader.result
//     }
// }
</script>
