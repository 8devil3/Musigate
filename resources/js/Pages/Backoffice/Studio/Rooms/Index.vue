<template>
    <ContentLayout
        as="div"
        title="Sale"
        icon="fa-solid fa-microphone-lines"
    >
        <template #content>
            <div v-if="props.rooms.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <article v-for="room in props.rooms" class="flex flex-col transition-colors border hover:shadow-xl border-slate-700 hover:border-orange-500 bg-slate-900 hover:bg-slate-800/50 rounded-2xl overflow-clip">
                    <Link :href="route('sale.edit', room.id)" class="relative block h-64">
                        <div class="absolute flex items-center justify-center text-xl leading-none text-white rounded-full shadow-md size-10 top-4 right-4" :class="!room.is_published ? 'bg-red-500' : 'bg-green-500'">
                            <i v-if="!room.is_published" title="Sala non visibile" class="fa-solid fa-eye-low-vision" />
                            <i v-else title="Sala visibile" class="fa-solid fa-eye" />
                        </div>

                        <img v-if="room.photos[0]" :src="'/storage/' + room.photos[0].path" class="object-cover w-full h-full" />
                        <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain w-full h-full p-6 lg:p-12 aspect-square bg-slate-950/50">
                    </Link>
                    
                    <div class="flex flex-col gap-4 p-4 grow">
                        <h2 class="flex items-center gap-2">
                            <!-- <span class="inline-block rounded-full shadow-inner size-5" :style="'background-color: ' + room.color" /> -->
                            {{ room.name }}
                        </h2>
    
                        <p v-if="room.description" class="text-sm line-clamp-3">
                            {{ room.description }}
                        </p>
    
                        <div class="flex justify-end gap-2 mt-auto">
                            <ActionButton type="router" :href="route('sale.edit', room.id)" icon="fa-solid fa-pen-to-square" title="Modifica sala" color="orange" />
                            <ActionButton @click="openModalDanger(room.id)" icon="fa-solid fa-trash-can" title="Elimina sala" color="red" />
                        </div>
                    </div>
                </article>
            </div>

            <Empty v-else icon="fa-solid fa-music">
                <template #title>
                    Non sono presenti Sale prova
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne una.
                </template>

                <template #actions>
                    <Button type="router" :href="route('sale.create')" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi"/>
                </template>
            </Empty>

            <ModalDanger :isOpen="isOpenModalDanger" @submitted="deleteRoom()" @close="closeModalDanger()">
                <template #title>
                    Eliminazione Sala
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

        <template #actions>
            <Button type="router" v-if="props.rooms.length" :href="route('sale.create')" text="Aggiungi Sala" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
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
    rooms: Object,
});

const isOpenModalDanger = ref(false);
const currentRoomId = ref(null);

const form = useForm({});

//elimina sala
const openModalDanger = (roomId)=>{
    currentRoomId.value = roomId;
    isOpenModalDanger.value = true;
};

const closeModalDanger = ()=>{
    isOpenModalDanger.value = false;
    currentRoomId.value = null;
};

const deleteRoom = ()=>{
    if(currentRoomId.value){
        form.delete(route('sale.destroy', currentRoomId.value), {
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
        title: 'Sale',
    }, {default: () => page}),
};
</script>
