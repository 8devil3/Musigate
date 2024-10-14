<template>
    <ContentLayout
        as="div"
        title="Collaborazioni"
        icon="fa-solid fa-handshake"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Inserisci le collaborazioni artistiche più importanti del tuo Studio.
                    Per ognuna puoi inserire un titolo con il nome del collaboratore e una breve descrizione.
                </template>

                <template #content>
                    <ul v-if="props.collaborations.length" class="relative w-full border-l-2 border-orange-500 md:max-w-lg md:mx-auto">                  
                        <li v-for="collab, idx in props.collaborations" class="p-3 mb-8 ml-4 rounded-lg bg-gray-800/50 last:mb-0">
                            <div class="absolute size-3.5 bg-gray-900 rounded-full mt-2 -left-2 border-2 border-orange-500" />
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <h3 class="text-gray-200">{{ collab.title }}</h3>
                                <div class="flex gap-2">
                                    <ActionButton @click="openModalCollab(collab.id, idx)" icon="fa-regular fa-pen-to-square" title="Modifica collaborazione" />
                                    
                                    <ActionButton @click="openModalDelete(collab.id)" icon="fa-regular fa-trash-can" color="red" title="Elimina collaborazione" />
                                </div>
                            </div>
                            <p class="text-sm text-gray-300">{{ collab.desc }}</p>
                        </li>
                    </ul>

                    <Empty v-else icon="fa-solid fa-handshake">
                        <template #title>
                            Nessuna collaborazione
                        </template>
                        <template #description>
                            Non sono presenti collaborazioni
                        </template>
                        <template #actions>
                            <Button @click="openModal = true" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi collaborazione" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <template v-if="props.collaborations.length" #actions>
            <Button @click="openModal = true" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi collaborazione" />
        </template>
    </ContentLayout>

    <Modal :isOpen="openModal" @close="closeModalCollab()">
        <template #title>
            <template v-if="collabId">Modifica collaborazione</template>
            <template v-else>Nuova collaborazione</template>
        </template>

        <template #description>
            <form v-if="collabId" @submit.prevent="submitEdit()" class="space-y-6">
                <Input id="studio-collabs-title" v-model="form.title" :error="form.errors.title" placeholder="Inserisci il titolo della collaborazione" required/>

                <Textarea id="studio-collab-desc" v-model="form.desc" :rows="12" :error="form.errors.desc" placeholder="Inserisci una breve descrizione della collaborazione" />
                
                <SaveButton />
            </form>
            
            <form v-else @submit.prevent="submitStore()" class="space-y-6">
                <Input id="studio-collabs-title" v-model="form.title" :error="form.errors.title" placeholder="Inserisci il titolo della collaborazione" required/>

                <Textarea id="studio-collab-desc" v-model="form.desc" :rows="12" :error="form.errors.desc" placeholder="Inserisci una breve descrizione della collaborazione" />
                
                <SaveButton />
            </form>
        </template>
    </Modal>
    
    <ModalDanger :isOpen="openModalDanger" @close="closeModalDelete()" @submitted="submitDelete()">
        <template #title>
            Elimina collaborazione
        </template>

        <template #description>
            Confermi di eliminare la collaborazione?
        </template>

        <template #actions>
            <Button type="submit" text="Sì, elimina" icon="fa-solid fa-trash-can" color="red" title="Elimina la collaborazione" :disabled="formDelete.processing" :isLoading="formDelete.processing"/>

            <Button @click="closeModalDelete()" text="Annulla" icon="fa-solid fa-xmark" color="gray" title="Annulla e torna indietro" />
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import ModalDanger from '@/Components/ModalDanger.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    collaborations: Array,
});

const openModal = ref(false);
const openModalDanger = ref(false);
const collabId = ref(null);

const form = useForm({
    title: null,
    desc: null,
});

const formDelete = useForm({});

const submitStore = ()=>{
    form.post(route('studio.collaborations.store'), {
        preserveState: false,
        onSuccess: ()=>{
            openModal.value = false;
            form.reset();
        }
    });
}


const openModalCollab = (collab_id, idx)=>{
    form.title = props.collaborations[idx].title;
    form.desc = props.collaborations[idx].desc;
    collabId.value = collab_id;
    openModal.value = true;
}

const closeModalCollab = ()=>{
    openModal.value = false;
    collabId.value = null;
    form.reset();
};

const submitEdit = ()=>{
    form.put(route('studio.collaborations.update', collabId.value), {
        preserveState: false,
        onSuccess: ()=>{
            openModal.value = false;
            collabId.value = null;
            form.reset();
        }
    });
}

const openModalDelete = (collab_id)=>{
    openModalDanger.value = true;
    collabId.value = collab_id;
}

const closeModalDelete = ()=>{
    openModalDanger.value = false;
    collabId.value = null;
}

const submitDelete = ()=>{
    formDelete.delete(route('studio.collaborations.delete', collabId.value), {
        preserveScroll: true,
        onSuccess: ()=>{
            openModalDanger.value = false;
            collabId.value = null;
        }
    });
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Collaborazioni',
    }, {default: () => page}),
};
</script>
