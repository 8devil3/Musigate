<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        title="Video"
        icon="fa-brands fa-youtube"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Inserisci i link YouTube dei video del tuo Studio. Non sono validi i link degli Short di YouTube.
                </template>
                <template #content>
                    <div v-if="form.videos.length" class="space-y-4">
                        <div v-for="video, id in form.videos">                            
                            <div class="flex items-center w-full gap-2">
                                <Input type="url" v-model="form.videos[id]" placeholder="https://www.youtube.com/watch?v={ID-VIDEO-YOUTUBE}" :error="form.errors['videos.' + id] ? 'Formato non valido' : null" class="w-full" required autofocus />
                                <ActionButton @click="removeVideo(id)" icon="fa-solid fa-trash-can" color="red" title="Elimina video" />
                            </div>
                            <div v-show="!checkYTVideoLink(form.videos[id])" class="px-4 py-2 text-red-500">Formato non valido</div>
                        </div>
                        
                        <div class="px-4">
                            <button type="button" @click="addVideo()" title="Aggiungi link video" class="p-0.5 font-medium text-orange-500 transition-colors hover:text-orange-400"><i class="mr-1 fa-solid fa-plus"></i> Aggiungi</button>
                        </div>
                    </div>

                    <Empty v-else icon="fa-brands fa-youtube">
                        <template #title>
                            Nessun link video di YouTube.
                        </template>
                        <template #description>
                            Non sono presenti link video di YouTube del tuo Studio.
                        </template>
                        <template #actions>
                            <Button @click="addVideo()" icon="fa-solid fa-plus" text="Aggiungi" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <template #actions>
            <SaveButton v-if="form.isDirty && !form.processing" />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    videos: Array,
});

const form = useForm({
    videos: props.videos,
});

const submit = ()=>{
    let checkVideoArr = [];
    form.videos.forEach(video => {
        checkVideoArr.push(checkYTVideoLink(video));
    });

    if(!checkVideoArr.includes(false)){
        form.put(route('studio.videos.update'), {
            preserveState: false,
        });
    }

}

const addVideo = ()=>{
    form.videos.push(null);
}

const removeVideo = (id)=>{
    form.videos.splice(id, 1);
}

const checkYTVideoLink = (ytLink)=>{
    const regex = /^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/;
    
    if(ytLink && ytLink.match(regex)){
        return ytLink;
    }
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Video',
    }, {default: () => page}),
};
</script>
