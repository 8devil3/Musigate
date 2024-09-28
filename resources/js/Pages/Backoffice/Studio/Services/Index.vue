<template>
    <ContentLayout
        as="div"
        title="Servizi"
        :isLoading="formUpdateStatus.processing || formDelete.processing"
        :onFail="formUpdateStatus.hasErrors || formDelete.hasErrors"
        icon="fa-solid fa-microphones-lines"
    >
        <template #description>
            Gestisci i servizi del tuo Studio.<br>
            Per modificare o eliminare un servizio dovrai prima sospenderlo.
        </template>

        <template #content>
            <div v-if="props.services.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <Link v-for="service in props.services" :href="route('servizi.edit', service.id)" class="block p-4 space-y-4 transition-colors border hover:shadow-xl border-slate-700 hover:border-orange-500 rounded-xl overflow-clip min-h-80">
                    <h2 class="flex items-center gap-2">
                        {{ service.name }}
                    </h2>

                    <img v-if="service.thuimbnail_path" :src="'/storage/' + service.thuimbnail_path.path" class="object-cover w-full aspect-video" />
                    <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain h-1/2 aspect-square">

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-hourglass-half" />
                            <span :class="{'line-through text-slate-400' : service.discounted_price}">{{ service.price }} €/h</span>
                            <span v-if="service.discounted_price">{{ service.discounted_price }} €/h</span>
                        </div>
                    </div>
                </Link>
            </div>

            <Empty v-else icon="fa-solid fa-microphone-lines">
                <template #title>
                    Non sono presenti servizi.
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne uno.
                </template>

                <template #actions>
                    <Button @click="addService()" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
                </template>
            </Empty>
        </template>

        <template #actions>
            <Button v-if="props.services.length" @click="addService()" text="Aggiungi" icon="fa-solid fa-plus" title="Aggiungi Sala"/>
        </template>
    </ContentLayout>

    <ModalDanger :isOpen="openModalDanger" @close="currentRoomId = null; openModalDanger = false">
        <template #title>
            Eliminazione servizio
        </template>

        <template #description>
            Confermi l'eliminazione del servizio?
        </template>

        <template #actions>
            <form @submit.prevent="formDelete()" class="space-x-2">
                <Button @click="deleteService()" text="Sì, elimina" color="red"/>
                <Button text="No, annulla" color="slate" @click="openModalDanger = false"/>
            </form>
        </template>
    </ModalDanger>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import RoomStatus from '@/Components/Backoffice/RoomStatus.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import Button from '@/Components/Form/Button.vue';
import Empty from '@/Components/Backoffice/Empty.vue';
import Carosello from '@/Components/Carosello.vue';
import ModalDanger from '@/Components/ModalDanger.vue';

const props = defineProps({
    services: Object,
    statuses: Object,
});

const openModalDanger = ref(false);
const currentRoomId = ref(null);

//status
const formUpdateStatus = useForm({
    room_status_id: 2
});

const formDelete = useForm({});

const updateStatus = (room_id)=>{
    formUpdateStatus.put(route('servizi.update_status', room_id));
}


//elimina sala
const deleteService = ()=>{
    formDelete.delete(route('servizi.delete', currentRoomId.value), {
        onFinish: () => {
            openModalDanger.value = false;
            currentRoomId.value = null;
        }
    })
}


//crea sala
const formNewRoom = useForm({});

const addService = ()=>{
    formNewRoom.post(route('servizi.store'))
}

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Servizi',
    }, {default: () => page}),
};
</script>
