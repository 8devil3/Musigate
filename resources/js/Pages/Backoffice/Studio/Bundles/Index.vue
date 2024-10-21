<template>
    <ContentLayout
        as="div"
        title="Pacchetti"
        icon="fa-solid fa-store"
    >
        <template #content>
            <div v-if="props.bundles.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <article v-for="bundle in props.bundles" class="flex flex-col transition-colors border hover:shadow-xl border-slate-700 hover:border-orange-500 bg-slate-900 hover:bg-slate-800/50 rounded-2xl overflow-clip">
                    <Link :href="route('pacchetti.edit', bundle.id)" class="relative block h-64">
                        <i v-if="!bundle.is_visible" title="Pacchetto non visibile" class="absolute flex items-center justify-center text-xl text-white bg-red-500 rounded-full shadow-md size-10 top-4 right-4 fa-solid fa-eye-low-vision" />
                        <i v-else title="Pacchetto visibile" class="absolute flex items-center justify-center text-xl text-white bg-green-500 rounded-full shadow-md size-10 top-4 right-4 fa-solid fa-eye" />

                        <img v-if="bundle.cover_path" :src="'/storage/' + bundle.cover_path" class="object-cover w-full h-full" />
                        <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full p-6 lg:p-12 aspect-square bg-slate-950/50">
                    </Link>
                    
                    <div class="flex flex-col gap-4 p-4 grow">
                        <h2 class="flex items-center gap-2">
                            <!-- <span class="inline-block rounded-full shadow-inner size-5" :style="'background-color: ' + bundle.color" /> -->
                            {{ bundle.name }}
                        </h2>
    
                        <p v-if="bundle.description" class="text-sm line-clamp-3">
                            {{ bundle.description }}
                        </p>
    
                        <div class="flex justify-end gap-2 mt-auto">
                            <ActionButton type="router" :href="route('pacchetti.edit', bundle.id)" icon="fa-solid fa-pen-to-square" title="Modifica pacchetto" color="orange" />
                            <ActionButton @click="openModalDanger(bundle.id)" icon="fa-solid fa-trash-can" title="Elimina pacchetto" color="red" />
                        </div>
                    </div>
                </article>
            </div>

            <Empty v-else icon="fa-solid fa-store">
                <template #title>
                    Non sono presenti pacchetti
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne uno.
                </template>

                <template #actions>
                    <Button type="router" :href="route('pacchetti.create')" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi"/>
                </template>
            </Empty>

            <ModalDanger :isOpen="isOpenModalDanger" @submitted="deleteBundle()" @close="closeModalDanger()">
                <template #title>
                    Eliminazione pacchetto
                </template>

                <template #description>
                    L'eliminazione è irreversibile, confermi di voler procedere?
                </template>

                <template #actions>
                    <Button type="submit" text="Sì, elimina" color="red"/>
                    <Button text="Annulla" color="slate" @click="closeModalDanger()"/>
                </template>
            </ModalDanger>
        </template>

        <template v-if="props.bundles.length" #actions>
            <Button type="router" :href="route('pacchetti.create')" text="Aggiungi pacchetto" icon="fa-solid fa-plus" title="Aggiungi pacchetto"/>
        </template>
    </ContentLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Button from '@/Components/Form/Button.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    bundles: Object,
});

const isOpenModalDanger = ref(false);
const currentBundleId = ref(null);

const form = useForm({});

//elimina pacchetto
const openModalDanger = (bundleId)=>{
    currentBundleId.value = bundleId;
    isOpenModalDanger.value = true;
};

const closeModalDanger = ()=>{
    isOpenModalDanger.value = false;
    currentBundleId.value = null;
};

const deleteBundle = ()=>{
    if(currentBundleId.value){
        form.delete(route('pacchetti.destroy', currentBundleId.value), {
            onFinish: () => {
                closeModalDanger();
            }
        })
    }
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Pacchetti',
    }, {default: () => page}),
};
</script>
