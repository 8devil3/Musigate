<template>
    <article class="relative flex flex-col w-full border border-gray-600 overflow-clip bg-gray-950 bg-opacity-60 rounded-2xl">
        <!-- badge sconto -->
        <div v-if="props.room.has_discounted_fixed_price || props.room.min_discounted_price" class="absolute z-50 w-40 py-1.5 text-xs font-medium text-center text-white origin-top rotate-45 translate-x-1/2 bg-orange-900/80 backdrop-blur-sm border-orange-500 shadow-sm border-y-2 top-2 right-2">
            <!-- <i class="-rotate-45 fa-solid fa-tag" /> -->
            <div>
                Tariffa<br>
                scontata
            </div>
        </div>
        <!-- / -->

        <!-- carosello -->
        <div class="h-60">
            <Carosello :imgs="props.room.photos" />
        </div>
        <!-- / -->

        <div class="flex flex-col gap-6 p-4 md:p-6 grow">
            <!-- icon bar -->
            <div class="flex items-center gap-6 font-normal">
                <div class="flex items-center whitespace-nowrap" title="Area in metri quadri">
                    <i class="mr-2 text-orange-500 fa-solid fa-ruler-combined"></i>
                    <span class="text-xs whitespace-nowrap">{{ props.room.area }} m²</span>
                </div>
                <div class="flex items-center" :title="'Massimo ' + (props.room.max_capacity > 1 ? props.room.max_capacity + ' ospiti' : props.room.max_capacity + ' ospite')">
                    <i class="mr-2 text-orange-500 fa-solid fa-users"></i>
                    <span class="text-xs whitespace-nowrap">{{ props.room.max_capacity }} max</span>
                </div>
                <div class="flex items-center whitespace-nowrap" title="Prenotazione minima">
                    <i class="mr-2 text-orange-500 fa-solid fa-hourglass-half"></i>
                    <span class="text-xs whitespace-nowrap">{{ props.room.min_booking === 1 ? ' min. 1 ora': 'min. ' + props.room.min_booking + ' ore' }}</span>
                </div>
            </div>
            <!-- / -->
            
            <!-- nome sala -->
            <h3 class="text-[18px] md:text-xl m-0">{{ props.room.name }}</h3>
            <!-- / -->

            <!-- descrizione -->
            <p class="m-0 line-clamp-3">
                {{ props.room.description }}
            </p>                
            <!-- / -->
            
            <!-- equipaggiamento -->
            <div v-if="props.room.equipments.length" class="space-y-2 grow">
                <h4 class="m-0">Equipaggiamento</h4>
    
                <ul class="m-0 list-musigate">
                    <template v-for="eq, index in props.room.equipments">
                        <li v-if="index <= 2" class="list-musigate">
                            {{ eq.name }}
                        </li>
                    </template>
                    <li class="list-musigate" v-if="props.room.equipments.length > 3">... altro</li>
                </ul>
            </div>
            <!-- / -->

            <!-- tariffa -->
            <div v-if="props.room.fixed_price || props.room.has_discounted_fixed_price || props.room.min_price || props.room.min_discounted_price" class="flex flex-col gap-1">
                Tariffe a partire da
                <template v-if="props.room.min_discounted_price">
                    <span class="text-xs line-through text-slate-400 font-lemon">
                        {{ props.room.min_price }} €/h
                    </span>
                    <span class="text-base font-medium font-lemon">
                        {{ props.room.min_discounted_price }} €/h
                    </span>
                </template>

                <span v-else-if="props.room.min_price" class="text-base font-medium font-lemon">
                    {{ props.room.min_price }} €/h
                </span>

                <template v-else-if="props.room.discounted_fixed_price">
                    <span class="text-xs line-through text-slate-400 font-lemon">
                        {{ props.room.fixed_price }} €/h
                    </span>
                    <span class="text-base font-medium font-lemon">
                        {{ props.room.discounted_fixed_price }} €/h
                    </span>
                </template>

                <span v-else class="text-base font-medium font-lemon">
                    {{ props.room.discounted_fixed_price }} €/h
                </span>
            </div>
            <!-- / -->

            <div class="flex justify-between gap-2">
                <ShowAll @click="openModalRoom = true" text="Dettagli Sala" />
                <Button type="router" v-if="props.room.is_bookable" :href="route('reservation.create', props.room.id)" text="Prenota" icon="fa-solid fa-calendar-days" />
            </div>
        </div>
    </article>

    <Modal :isOpen="openModalRoom" @close="openModalRoom = false">
        <template #title>
            {{ props.room.name }}
        </template>
        <template #description>
            <Carosello :imgs="props.room.photos" class="h-48 shrink-0 md:h-96" />
            
            <div class="flex flex-col gap-8 pt-6 grow">
                <!-- icon bar -->
                <div class="flex items-center gap-6 overflow-x-auto font-normal noscrollbar">
                    <div class="flex items-center whitespace-nowrap" title="Area in metri quadri">
                        <i class="mr-2 text-orange-500 fa-solid fa-ruler-combined"></i>
                        <span class="text-xs whitespace-nowrap">{{ props.room.area }} mq</span>
                    </div>
                    <div class="flex items-center" :title="'Massimo ' + (props.room.max_capacity > 1 ? props.room.max_capacity + ' ospiti' : props.room.max_capacity + ' ospite')">
                        <i class="mr-2 text-orange-500 fa-solid fa-users"></i>
                        <span class="text-xs whitespace-nowrap">{{ props.room.max_capacity }} max</span>
                    </div>
                    <div class="flex items-center whitespace-nowrap" title="Prenotazione minima">
                        <i class="mr-2 text-orange-500 fa-solid fa-hourglass-half"></i>
                        <span class="text-xs whitespace-nowrap">{{ props.room.min_booking === 1 ? ' min. 1 ora': 'min. ' + props.room.min_booking + ' ore' }}</span>
                    </div>
                </div>
                <!-- / -->

                <!-- descrizione -->
                <div class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Descrizione</h4>
                    <p class="m-0">
                        {{ props.room.description }}
                    </p>
                </div>
                <!-- / -->
                
                <!-- equipaggiamento -->
                <div v-if="props.room.equipments.length" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Equipaggiamento</h4>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <template v-for="category, key in props.equipment_categories">
                            <div v-if="props.room.equipments.filter(equip => equip.equipment_category_id == key).length">
                                <h5 class="mb-1 text-sm font-lemon">{{ category }}</h5>
                                <ul class="grid grid-cols-1 m-0 gap-x-16 list-musigate">
                                    <li v-for="eq in props.room.equipments.filter(equip => equip.equipment_category_id == key)" class="w-full list-musigate">
                                        <div class="truncate">{{ eq.name }}</div>
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- / -->
            </div>
        </template>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import ShowAll from '@/Components/ShowAll.vue';
import Carosello from '@/Components/Carosello.vue';
import Button from '@/Components/Form/Button.vue';

const props = defineProps({
    room: Object,
    equipment_categories: Object,
});

const openModalRoom = ref(false);

</script>
