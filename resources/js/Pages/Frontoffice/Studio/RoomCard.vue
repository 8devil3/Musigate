<template>
    <article class="relative flex flex-col w-full border border-slate-600 overflow-clip bg-slate-950 bg-opacity-60 rounded-2xl">
        <!-- badge sconto -->
        <!-- <div v-if="props.room.has_discounted_fixed_price || props.room.min_discounted_price" class="absolute z-10 w-40 py-1.5 text-xs font-medium text-center text-white origin-top rotate-45 translate-x-1/2 bg-orange-900/80 backdrop-blur-sm border-orange-500 shadow-sm border-y-2 top-2 right-2">
            <div>
                Tariffe<br>
                scontate
            </div>
        </div> -->
        <!-- / -->

        <!-- carosello -->
        <div class="h-60">
            <Carosello :imgs="props.room.photos" />
        </div>
        <!-- / -->

        <div class="flex flex-col gap-6 p-4 md:p-6 grow">
            <!-- icon bar -->
            <ul class="flex items-center gap-6 p-0 font-normal">
                <li class="flex items-center p-0 whitespace-nowrap" title="Area in metri quadri">
                    <i class="mr-2 text-orange-500 fa-solid fa-ruler-combined" />
                    <span class="text-xs">{{ props.room.area }} mq</span>
                </li>
                <li class="flex items-center p-0 whitespace-nowrap" :title="'Massimo ' + (props.room.max_capacity > 1 ? props.room.max_capacity + ' ospiti' : props.room.max_capacity + ' ospite')">
                    <i class="mr-2 text-orange-500 fa-solid fa-users" />
                    <span class="text-xs">{{ props.room.max_capacity }} max</span>
                </li>
                <li class="flex items-center p-0 whitespace-nowrap" title="Prenotazione minima">
                    <i class="mr-2 text-orange-500 fa-solid fa-hourglass-half" />
                    <span class="text-xs">{{ props.room.min_booking === 1 ? ' min. 1 ora': 'min. ' + props.room.min_booking + ' ore' }}</span>
                </li>
            </ul>
            <!-- / -->
            
            <div class="space-y-2">
                <!-- nome sala -->
                <h3 class="text-[18px] md:text-xl">{{ props.room.name }}</h3>
                <!-- / -->
    
                <!-- descrizione -->
                <p v-if="props.room.description" class="p-0 line-clamp-3">
                    {{ props.room.description }}
                </p>                
                <!-- / -->
            </div>

            <!-- tariffe -->
            <div v-if="props.room.price_type !== 'no_price'" class="space-y-1">
                <div class="text-sm">
                    A partire da
                </div>
                <div class="flex items-end gap-2 leading-none">
                    <template v-if="props.room.price_type === 'timebands_price'">
                        <template v-if="props.room.min_discounted_timeband_price">
                            <span class="mb-1 text-xs line-through text-slate-400 font-lemon">
                                {{ props.room.min_timeband_price /100 }} €/h
                            </span>
                            <span class="text-base font-medium font-lemon">
                                {{ props.room.min_discounted_timeband_price /100 }} €/h
                            </span>
                        </template>
                        <span v-else="props.room.min_timeband_price" class="text-base font-medium font-lemon">
                            {{ props.room.min_timeband_price /100 }} €/h
                        </span>
                    </template>

                    <template v-else-if="props.room.price_type === 'hourly_price'">
                        <template v-if="props.room.min_discounted_hourly_price">
                            <span class="mb-1 text-xs line-through text-slate-400 font-lemon">
                                {{ props.room.min_hourly_price /100 }} €/h
                            </span>
                            <span class="text-base font-medium font-lemon">
                                {{ props.room.min_discounted_hourly_price /100 }} €/h
                            </span>
                        </template>
                        <span v-else="props.room.min_hourly_price" class="text-base font-medium font-lemon">
                            {{ props.room.min_hourly_price /100 }} €/h
                        </span>
                    </template>

                    <template v-else-if="props.room.price_type === 'monthly_price'">
                        <template v-if="props.room.has_discounted_monthly_price">
                            <span class="mb-1 text-xs line-through text-slate-400 font-lemon">
                                {{ props.room.monthly_price }} €/mese
                            </span>
                            <span class="text-base font-medium font-lemon">
                                {{ props.room.discounted_monthly_price }} €/mese
                            </span>
                        </template>
                        <span v-else class="text-base font-medium font-lemon">
                            {{ props.room.monthly_price }} €/mese
                        </span>
                    </template>
                </div>
            </div>
            <!-- / -->

            <div class="flex justify-between gap-2 mt-auto">
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
            <div class="h-64 shrink-0 md:h-96">
                <Carosello :imgs="props.room.photos" />
            </div>
            
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
                <div v-if="props.room.description" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Descrizione</h4>
                    <p class="m-0">
                        {{ props.room.description }}
                    </p>
                </div>
                <!-- / -->

                <!-- tariffe -->
                <div v-if="props.room.price_type !== 'no_price'" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Tariffe</h4>

                    <div v-if="props.room.price_type === 'timebands_price'" class="grid grid-cols-1 gap-6 pt-4 sm:grid-cols-2">
                        <template v-for="wd, wdKey in props.weekdays">
                            <div v-if="props.room.timeband_prices.filter(price => price.timeband.weekday == wdKey).length" class="pb-4 space-y-4 border-b border-slate-700 last-of-type:border-0 sm:[&:nth-last-of-type(-n+2)]:border-0">
                                <h5 class="text-white capitalize">
                                    {{ wd }}
                                </h5>
                                <div v-for="price in props.room.timeband_prices.filter(price => price.timeband.weekday == wdKey)" class="space-y-1 font-normal">
                                    <div>
                                        <div class="text-sm text-slate-200">
                                            Fascia oraria
                                            {{ price?.timeband.name }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ price.timeband.start }}
                                            -
                                            {{ price.timeband.end }}
                                        </div>
                                    </div>
                                    <div v-if="price.has_discounted_price" class="text-white">
                                        <span class="text-xs line-through text-slate-400">{{ price.price }} €/h</span>
                                        {{ price.discounted_price }} €/h
                                    </div>
                                    <div v-else class="text-white">
                                        {{ price.price }} €/h
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div v-if="props.room.price_type === 'hourly_price'" class="grid grid-cols-1 gap-6 pt-4 sm:grid-cols-2">
                        <template v-for="wd, wdKey in props.weekdays">
                            <div v-if="props.room.hourly_prices.filter(price => price.weekday == wdKey).length" class="pb-4 space-y-2 border-b border-slate-700 last-of-type:border-0 sm:[&:nth-last-of-type(-n+2)]:border-0">
                                <h5 class="text-white capitalize">
                                    {{ wd }}
                                </h5>
                                <div v-for="price in props.room.hourly_prices.filter(price => price.weekday == wdKey)" class="space-y-1 font-normal">
                                    <div v-if="price.has_discounted_price" class="text-white">
                                        <span class="text-xs line-through text-slate-400">{{ price.price }} €/h</span>
                                        {{ price.discounted_price }} €/h
                                    </div>
                                    <div v-else class="text-white">
                                        {{ price.price }} €/h
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div v-else-if="props.room.price_type === 'monthly_price'">
                        <template v-if="props.room.discounted_monthly_price">
                            <span class="text-xs line-through text-slate-400 font-lemon">
                                {{ props.room.monthly_price }} €/mese
                            </span>
                            <span class="ml-2 text-base font-medium font-lemon">
                                {{ props.room.discounted_monthly_price }} €/mese
                            </span>
                        </template>
                        <span v-else class="text-base font-medium font-lemon">
                            {{ props.room.discounted_monthly_price }} €/mese
                        </span>
                    </div>
                </div>
                <!-- / -->

                <!-- equipaggiamento -->
                <div v-if="props.room.equipments.length" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Equipaggiamento</h4>

                    <div class="space-y-4">
                        <template v-for="category, id in props.equipment_categories">
                            <div v-if="props.room.equipments.filter(equip => equip.equipment_category_id == id).length">
                                <h5 class="mb-2">{{ category }}</h5>
                                <ul class="list-musigate">
                                    <li v-for="eq in props.room.equipments.filter(equip => equip.equipment_category_id == id)" :title="eq.name">
                                        <div>{{ eq.name }}</div>
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
    weekdays: Object
});

const openModalRoom = ref(false);

</script>
