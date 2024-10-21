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
                    Per ognuna puoi inserire un titolo con il nome del collaboratore, una breve descrizione e i link Spotify, SoundCloud e iTunes.
                    Quando crei o modifichi una collaborazione, Musigate le ordinerà in ordine cronologica dalla più recente alla più remota.
                </template>

                <template #content>
                    <ul v-if="props.collaborations.length" class="relative w-full list-none border-l-2 border-orange-500 md:max-w-lg md:mx-auto list-image-none">                  
                        <li v-for="collab in props.collaborations" class="p-3 mb-8 ml-4 rounded-lg bg-slate-800/50 last:mb-0">
                            <div class="absolute size-3.5 bg-slate-900 rounded-full mt-2 -left-2 border-2 border-orange-500" />
                            <div class="space-y-2">
                                <div>
                                    <h3 class="m-0 font-sans text-xs font-normal uppercase text-slate-400">{{ props.months[collab.month] }} {{ collab.year }}</h3>
                                    <h2 class="text-base leading-normal text-slate-200">{{ collab.title }}</h2>
                                </div>

                                <p class="text-sm text-slate-300">{{ collab.description }}</p>

                                <div class="space-x-2">
                                    <a v-if="collab.spotify" :href="collab.spotify" class="transition-colors hover:text-orange-500 text-[#25D865]">
                                        <i class="text-xl fa-brands fa-spotify" />
                                    </a>
                                    <a v-if="collab.soundcloud" :href="collab.soundcloud" class="transition-colors hover:text-orange-500 text-[#e56431]">
                                        <i class="text-xl fa-brands fa-soundcloud" />
                                    </a>
                                    <a v-if="collab.itunes" :href="collab.itunes" class="transition-colors hover:text-orange-500 text-[#8F60FF]">
                                        <i class="text-xl fa-brands fa-itunes" />
                                    </a>
                                </div>

                                <div class="space-x-2 text-right">
                                    <ActionButton @click="router.get(route('studio.collaborazioni.edit', collab.id))" icon="fa-solid fa-pen-to-square" title="Modifica collaborazione" />
                                    <ActionButton @click="openModalDelete(collab.id)" icon="fa-solid fa-trash-can" color="red" title="Elimina collaborazione" />
                                </div>
                            </div>
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
                            <Button @click="router.get(route('studio.collaborazioni.create'))" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi collaborazione" />
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <template v-if="props.collaborations.length" #actions>
            <Button @click="router.get(route('studio.collaborazioni.create'))" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi collaborazione" />
        </template>
    </ContentLayout>

    <ModalDanger :isOpen="openModalDanger" @close="closeModalDelete()" @submitted="deleteCollaboration()">
        <template #title>
            Elimina collaborazione
        </template>

        <template #description>
            Confermi di eliminare la collaborazione?
        </template>

        <template #actions>
            <Button type="submit" text="Sì, elimina" color="red" title="Elimina la collaborazione" />
            <Button @click="closeModalDelete()" text="Annulla" color="slate" title="Annulla e torna indietro" />
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Button from '@/Components/Form/Button.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    collaborations: Object,
    months: Object,
});

const openModalDanger = ref(false);
const collabId = ref(null);

const openModalDelete = (collab_id)=>{
    openModalDanger.value = true;
    collabId.value = collab_id;
};

const closeModalDelete = ()=>{
    openModalDanger.value = false;
    collabId.value = null;
};

const deleteCollaboration = ()=>{
    router.delete(route('studio.collaborazioni.destroy', collabId.value), {
        preserveScroll: true,
        onSuccess: ()=> closeModalDelete(),
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Collaborazioni',
    }, {default: () => page}),
};
</script>
