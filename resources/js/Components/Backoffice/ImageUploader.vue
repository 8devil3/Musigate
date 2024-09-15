<template>
    <div class="space-y-4">
        <form v-if="props.modelValue !== null" @submit.prevent="deleteImage()" class="flex items-center gap-4">
            <div :for="props.id" class="relative flex items-center justify-center w-32 h-32 border-2 border-gray-500 border-dashed rounded-full shrink-0">
                <img :src="'/storage/' + props.modelValue" alt="Img" class="object-contain w-full h-full rounded-full" />

                <button v-if="props.modelValue" type="submit" title="Elimina" class="absolute z-10 flex items-center justify-center w-6 h-6 leading-none transition-colors border border-red-600 rounded-full -top-1 -right-4 bg-red-600/10 hover:bg-red-600/30">
                    <i class="text-xs text-red-500 fa-solid fa-trash-can"></i>
                </button>
            </div>
        </form>

        <form v-else @submit.prevent="uploadImage()" class="flex items-center gap-4">
            <label :for="props.id" title="Carica logo" class="flex flex-col items-center justify-center w-32 h-32 gap-1 text-center transition-opacity border-2 border-gray-500 border-dashed rounded-full cursor-pointer hover:opacity-80 shrink-0">
                <template v-if="!imagePreview && !props.modelValue">
                    <i class="text-4xl text-gray-600 fa-solid fa-upload"></i>
                    <div class="text-xs text-gray-400">
                        Click o tap per caricare.
                    </div>
                </template>

                <img v-else-if="imagePreview" :src="imagePreview" alt="Img" class="object-contain w-full h-full rounded-full" />

                <input type="file" :id="props.id" @change="preview($event.target.files[0])" :accept="props.accept" hidden />
            </label>
            
            <div class="space-y-2">                
                <Button v-if="formUpload.file" type="submit" text="Carica" icon="fa-solid fa-upload" color="green" :isLoading="formUpload.processing" :disabled="formUpload.processing"/>
    
                <ProgressBar :progress="formUpload.progress" />
            </div>
        </form>
                                    
        <div v-show="formUpload.errors.file" class="text-sm text-left text-red-600">{{ formUpload.errors.file }}</div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ProgressBar from '@/Components/Form/ProgressBar.vue';
import Button from '@/Components/Form/Button.vue';

const props = defineProps({
    modelValue: String,
    routeUpload: String,
    routeDelete: String,
    accept: String,
    maxSizeMB: Number,
    id: String,
});

const emit = defineEmits([
    'update:model-value'
]);

const imagePreview = ref(null);

const formUpload = useForm({
    _method: 'PUT',
    file: props.modelValue,
});

const formDelete = useForm({});

const uploadImage = ()=>{
    formUpload.post(props.routeUpload, {
        preserveScroll: true,
        onSuccess: ()=>{
            formUpload.reset();
            imagePreview.value = null;
        }
    });
}

const deleteImage = ()=>{
    formDelete.delete(props.routeDelete, {
        preserveScroll: true,
        onSuccess: ()=>{
            formDelete.reset();
            formUpload.file = null;
            imagePreview.value = null;
        }
    });
}


const preview = (e) => {
    formUpload.file = e

    let reader = new FileReader();
    reader.readAsDataURL(e);
    reader.onload = ()=>{
        imagePreview.value = reader.result
    }
}
</script>
