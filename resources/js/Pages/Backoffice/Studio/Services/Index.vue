<template>
    <ContentLayout
        as="div"
        title="Serivizi"
        :isLoading="form.processing"
        icon="fa-solid fa-microphone-lines"
    >
        <template #content>
            <div v-if="props.services.length" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3 lg:gap-6">
                <article v-for="service in props.services" class="flex flex-col gap-4 p-4 transition-colors border hover:shadow-xl border-slate-700 hover:border-orange-500 hover:bg-slate-800/50 rounded-xl overflow-clip min-h-80">
                    <h2 class="flex items-center gap-2">
                        <span class="inline-block w-5 h-5 rounded-full shadow-inner" :style="'background-color: ' + service.color" />
                        {{ service.name }}
                    </h2>

                    <Link :href="route('servizi.edit', service.id)" class="block">
                        <img v-if="service.thumbnail_path" :src="'/storage/' + service.thumbnail_path" class="object-cover w-full aspect-video" />
                        <img v-else src="/img/logo/logo_placeholder.svg" class="object-contain w-full p-6 lg:p-12 aspect-square">
                    </Link>

                    <div class="flex gap-6 text-xs">
                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-ruler-combined" />
                            {{ service.area }} mq
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-euro" />
                            <span :class="{'line-through text-slate-400' : service.has_discounted_price}">{{ service.price }} €/h</span>
                            <span v-if="service.has_discounted_price">{{ service.discounted_price }} €/h</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="text-orange-500 fa-solid fa-users" />
                            max {{ service.max_capacity }}
                        </div>
                    </div>

                    <p class="text-sm line-clamp-3">
                        {{ service.description }}
                    </p>

                    <div class="flex justify-end gap-2 pt-4 mt-auto">
                        <ActionButton type="router" :href="route('servizi.edit', service.id)" icon="fa-solid fa-pen-to-square" title="Modifica servizio" color="orange" />
                        <ActionButton @click="openModalDanger(service.id)" icon="fa-solid fa-trash-can" title="Elimina sala" color="red" />
                    </div>
                </article>
            </div>

            <Empty v-else icon="fa-solid fa-music">
                <template #title>
                    Non sono presenti servizi
                </template>

                <template #description>
                    Clicca sul pulsante "Aggiungi" per crearne uno.
                </template>

                <template #actions>
                    <Button type="router" :href="route('servizi.create')" text="Crea un servizio" icon="fa-solid fa-plus" title="Crea un servizio"/>
                </template>
            </Empty>

            <ModalDanger :isOpen="isOpenModalDanger" @submitted="deleteRoom()" @close="closeModalDanger()">
                <template #title>
                    Eliminazione servizio
                </template>

                <template #description>
                    Confermi l'eliminazione del servizio?
                </template>

                <template #actions>
                    <Button type="submit" text="Sì, elimina" color="red"/>
                    <Button text="Annulla" color="slate" @click="closeModalDanger()"/>
                </template>
            </ModalDanger>
        </template>

        <template #actions>
            <Button type="router" v-if="props.services.length" :href="route('servizi.create')" text="Aggiungi servizio" icon="fa-solid fa-plus" title="Aggiungi servizio"/>
        </template>
    </ContentLayout>
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
    services: Object,
    statuses: Object,
});

const isOpenModalDanger = ref(false);
const currentServiceId = ref(null);

const form = useForm({});

//elimina servizio
const openModalDanger = (roomId)=>{
    currentServiceId.value = roomId;
    isOpenModalDanger.value = true;
};

const closeModalDanger = ()=>{
    isOpenModalDanger.value = false;
    currentServiceId.value = null;
};

const deleteRoom = ()=>{
    if(currentServiceId.value){
        form.delete(route('servizi.destroy', currentServiceId.value), {
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
        title: 'Serivizi',
    }, {default: () => page}),
};
</script>
