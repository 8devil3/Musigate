<template>
    <ContentLayout
        as="div"
        title="Sale prova"
        :isLoading="formDelete.processing"
        :onFail="formDelete.hasErrors"
        icon="fa-solid fa-music"
    >
        <template #description>
            Gestisci le Sale del tuo Studio.<br>
            Per modificare o eliminare una Sala dovrai prima sospenderla.
        </template>

        <template #content>
            <div v-if="props.rooms.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <article v-for="room in props.rooms" class="flex flex-col gap-4 p-4 transition-colors border hover:shadow-xl border-slate-700 hover:border-orange-500 hover:bg-slate-800/50 rounded-xl overflow-clip min-h-80">
                    <Link :href="route('rooms.edit', room.id)" class="block">
                        <h2 class="flex items-center gap-2">
                            <span class="inline-block w-5 h-5 rounded-full shadow-inner" :style="'background-color: ' + room.color" />
                            {{ room.name }}
                        </h2>
    
                        <img v-if="room.photos[0]" :src="'/storage/' + room.photos[0].path" class="object-cover w-full aspect-video" />
                        <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain w-full p-6 lg:p-12 aspect-square">
    
                    </Link>

                    <div class="flex gap-6 text-xs">
                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-ruler-combined" />
                            {{ room.area }} mq
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-euro" />
                            <span :class="{'line-through text-slate-400' : room.discounted_price}">{{ room.price }} €/h</span>
                            <span v-if="room.discounted_price">{{ room.discounted_price }} €/h</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-users" />
                            max {{ room.max_capacity }}
                        </div>
                    </div>

                    <p class="text-sm line-clamp-3">
                        {{ room.description }}
                    </p>

                    <div class="flex justify-end gap-2 pt-4 mt-auto">
                        <ActionButton type="router" :href="route('rooms.edit', room.id)" icon="fa-solid fa-pen-to-square" title="Modifica sala" color="orange" />
                        <ActionButton @click="openModalDanger(room.id)" icon="fa-solid fa-trash-can" title="Elimina sala" color="red" />
                    </div>
                </article>
            </div>

            <Empty v-else icon="fa-solid fa-microphone-lines">
                <template #title>
                    Non sono presenti Sale Studio.
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne una.
                </template>

                <template #actions>
                    <Button type="router" :href="route('rooms.create')" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
                </template>
            </Empty>
        </template>

        <template #actions>
            <Button type="router" v-if="props.rooms.length" :href="route('rooms.create')" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
        </template>
    </ContentLayout>

    <ModalDanger :isOpen="isOpenModalDanger" @submitted="deleteRoom()" @close="closeModalDanger()">
        <template #title>
            Eliminazione Sala
        </template>

        <template #description>
            <strong class="text-red-500">Attenzione:</strong> eliminando la sala verranno eliminate anche le relative prenotazioni. Se non vuoi più ricevere prenotazioni per questa sala puoi renderla non prenotabile o invisibile.<br>
            L'eliminazione è irreversibile.
        </template>

        <template #actions>
            <Input v-model="formDelete.confirmDeletion" placeholder="Digita la parola 'elimina' per confermare l'eliminazione" required class="w-full" />

            <div class="mt-6 space-x-2">
                <Button type="submit" text="Elimina" color="red"/>
                <Button text="Annulla" color="slate" @click="closeModalDanger()"/>
            </div>
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    rooms: Object,
    statuses: Object,
});

const isOpenModalDanger = ref(false);
const currentRoomId = ref(null);

const formDelete = useForm({
    confirmDeletion: null,
});


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
    if(currentRoomId.value && formDelete.confirmDeletion){
        formDelete.delete(route('rooms.delete', currentRoomId.value), {
            onFinish: () => {
                closeModalDanger();
            }
        })
    }
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Sale prova',
    }, {default: () => page}),
};
</script>
