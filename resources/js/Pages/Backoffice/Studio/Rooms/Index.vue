<template>
    <BackofficeLayout as="div" title="Sale Studio" :isLoading="formUpdateStatus.processing || formDelete.processing" :onFail="formUpdateStatus.hasErrors || formDelete.hasErrors" icon="fa-solid fa-microphone-lines">
        <template #description>
            Gestisci le Sale del tuo Studio.<br>
            Per modificare o eliminare una Sala dovrai prima sospenderla.
        </template>

        <template #content>
            <div v-if="props.rooms.length" class="overflow-x-auto bg-gray-800">
                <table class="w-full divide-y divide-gray-600">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase">Nome</th>
                            <th scope="col" class="hidden px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase sm:table-cell">Status</th>
                            <th scope="col" class="hidden px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase sm:table-cell">Area</th>
                            <th scope="col" class="hidden px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase sm:table-cell">Prenot. minima</th>
                            <th scope="col" class="hidden px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase sm:table-cell">Capienza max</th>
                            <th scope="col" class="px-6 py-3 text-sm font-medium tracking-wider text-left text-gray-300 uppercase">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        <tr v-for="room in props.rooms">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-100">
                                    <!-- <div :title="room.color" class="w-8 h-8 rounded-full shadow-inner shrink-0" :style="'background-color:' + room.color" /> -->
                                    <div class="text-base">{{ room.name }}</div>
                                    <p class="mb-2 text-sm text-gray-400">{{ props.room_types[room.room_type_id] }}</p>
                                    <RoomStatus :statusId="room.room_status_id" :statusName="props.room_statuses[room.room_status_id]" class="sm:hidden" />
                                </div>
                            </td>
                            <td class="hidden px-6 py-4 sm:table-cell">
                                <RoomStatus :statusId="room.room_status_id" :statusName="props.room_statuses[room.room_status_id]" />
                            </td>
                            <td class="hidden px-6 py-4 text-sm text-gray-400 sm:table-cell whitespace-nowrap">
                                <p v-if="room.area">{{ room.area }} mq</p>
                            </td>
                            <td class="hidden px-6 py-4 text-sm text-gray-400 sm:table-cell whitespace-nowrap">
                                <p v-if="room.min_booking">{{ room.min_booking == 1 ? room.min_booking + ' ora' : room.min_booking + ' ore'}}</p>
                            </td>
                            <td class="hidden px-6 py-4 text-sm text-gray-400 sm:table-cell whitespace-nowrap">
                                <p v-if="room.max_capacity">{{ room.max_capacity == 1 ? room.max_capacity + ' persona' : room.max_capacity + ' persone' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <form @submit.prevent="updateStatus(room.id)" class="flex items-center gap-2">
                                    <ActionButton @click="router.get(route('rooms.description.edit', room.id))" v-if="room.room_status_id === 1 || room.room_status_id === 2 || room.room_status_id === 5" icon="fa-regular fa-pen-to-square" title="Modifica Sala" />

                                    <ActionButton type="submit" v-if="room.room_status_id === 2 || room.room_status_id === 5" @click="formUpdateStatus.room_status_id = 4" icon="fa-regular fa-circle-play" color="green" title="Pubblica Sala" />

                                    <ActionButton type="submit" v-if="room.room_status_id === 4" @click="formUpdateStatus.room_status_id = 2" icon="fa-regular fa-circle-pause" color="gray" title="Sospendi Sala" />

                                    <ActionButton v-if="room.room_status_id === 2 || room.room_status_id === 5" @click="currentRoomId = room.id; openModalDanger = true" icon="fa-regular fa-trash-can" color="red" title="Elimina Sala" />
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Empty v-else icon="fa-solid fa-microphone-lines">
                <template #title>
                    Non sono presenti Sale Studio.
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne una.
                </template>

                <template #actions>
                    <Button @click="createNewRoom()" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
                </template>
            </Empty>
        </template>

        <template #actions>
            <Button v-if="props.rooms.length" @click="createNewRoom()" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
        </template>
    </BackofficeLayout>

    <ModalDanger :isOpen="openModalDanger" @close="currentRoomId = null; openModalDanger = false">
        <template #title>
            Eliminazione Sala
        </template>

        <template #description>
            Confermi l'eliminazione della Sala?
        </template>

        <template #actions>
            <form @submit.prevent="formDelete()" class="space-x-2">
                <Button @click="deleteRoom()" text="Sì, elimina" color="red"/>
                <Button text="No, annulla" color="gray" @click="openModalDanger = false"/>
            </form>
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import RoomStatus from '@/Components/Backoffice/RoomStatus.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Button from '@/Components/Form/Button.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    rooms: Object,
    room_types: Object,
    room_statuses: Object,
    booking_advances: Object,
});

const openModalDanger = ref(false);
const currentRoomId = ref(null);

//status
const formUpdateStatus = useForm({
    room_status_id: 2
});

const formDelete = useForm({});

const updateStatus = (room_id)=>{
    formUpdateStatus.put(route('rooms.update_status', room_id));
}


//elimina sala
const deleteRoom = ()=>{
    formDelete.delete(route('rooms.delete', currentRoomId.value), {
        onFinish: () => {
            openModalDanger.value = false;
            currentRoomId.value = null;
        }
    })
}


//crea sala
const formNewRoom = useForm({});

const createNewRoom = ()=>{
    formNewRoom.post(route('rooms.store'))
}

</script>
