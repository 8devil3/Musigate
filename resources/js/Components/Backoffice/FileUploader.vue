<template>
    <form @submit.prevent="deleteFile()" v-if="props.modelValue !== null" class="flex items-center gap-6">
        <a :href="'/storage/' + props.modelValue" target="_blank" class="underline transition-colors text-emerald-600 hover:text-emerald-500">
            {{ props.fileName }}
        </a>
        
        <Button type="submit" icon="fa-solid fa-trash" color="red" text="Delete" :isLoading="form.processing" :disabled="form.processing"/>
    </form>

    <div v-else class="space-y-4 text-center">
        <form @submit.prevent="uploadFile()" class="space-y-4">
            <label :for="props.id" class="block p-4 space-y-2 text-center bg-gray-100 border-2 border-gray-500 border-dashed rounded-lg cursor-pointer hover:bg-gray-200">
                <i class="text-3xl text-gray-600 fa-solid fa-cloud-arrow-up"></i>
                <p v-if="form.file" class="text-sm font-medium text-gray-600">{{ form.file.name }}</p>
                <p v-else class="text-sm font-medium text-gray-600">{{ lang.file_uploader['Click to upload file'] }}</p>
                <input type="file" :id="props.id" @change="form.file = $event.target.files[0]" @drop.prevent="form.file = $event.target.files[0]" :accept="props.accept" hidden />
                <div class="text-xs">{{ lang.file_uploader['Accepted formats'] }}: {{ props.accept }}, max {{ props.maxSizeMB }}MB</div>
            </label>

            <ProgressBar :progress="form.progress" />

            <Button v-if="form.file" type="submit" text="Upload" icon="fa-solid fa-upload" :isLoading="form.processing" :disabled="form.processing"/>
        </form>
                                    
        <div v-show="form.errors.file" class="text-sm text-left text-red-600">{{ form.errors.file }}</div>
    </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import ProgressBar from '@/Components/Form/ProgressBar.vue';
import Button from '@/Components/Form/Button.vue';

const props = defineProps({
    modelValue: String,
    routeUpload: String,
    routeDelete: String,
    routeId: Number,
    accept: String,
    maxSizeMB: Number,
    id: String,
    field_name: String,
    fileName: String
});

const emit = defineEmits([
    'update:model-value'
]);

const lang = usePage().props.localization;

const form = useForm({
    _method: 'PUT',
    file: props.modelValue,
    field_name: props.field_name
});

const uploadFile = ()=>{
    form.post(route(props.routeUpload, props.routeId), {
        preserveScroll: true,
        onSuccess: ()=>{
            form.reset();
        }
    });
}

const deleteFile = ()=>{
    form.delete(route(props.routeDelete, props.routeId), {
        preserveScroll: true,
        onSuccess: ()=>{
            form.reset();
        }
    });
}
</script>
