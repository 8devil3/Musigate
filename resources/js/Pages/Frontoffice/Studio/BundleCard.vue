<template>
    <article class="relative flex flex-col w-full border border-gray-600 overflow-clip bg-gray-950 bg-opacity-60 rounded-2xl">
        <!-- copertina -->
         <div class="h-60">
             <img v-if="props.bundle.cover_path" :src="'/storage/' + props.bundle.cover_path" :alt="props.bundle.name" class="object-cover w-full h-full" />
             <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.bundle.name" class="object-contain w-full h-full p-14 bg-slate-800/40" />
         </div>
        <!-- / -->

        <div class="flex flex-col gap-6 p-4 md:p-6 grow">            
            <div class="space-y-2">
                <!-- nome sala -->
                <h3 class="text-[18px] md:text-xl">{{ props.bundle.name }}</h3>
                <!-- / -->
    
                <!-- descrizione -->
                <p v-if="props.bundle.description" class="line-clamp-3">
                    {{ props.bundle.description }}
                </p>                
                <!-- / -->
            </div>

            <!-- prezzi -->
            <div v-if="props.bundle.fixed_price || props.bundle.has_discounted_fixed_price || props.bundle.min_price || props.bundle.min_discounted_price" class="space-y-1">
                <div>
                    Prezzo minimo
                </div>
                <div class="flex items-end gap-3">
                    <template v-if="props.bundle.min_discounted_price">
                        <span class="mb-1 text-xs line-through text-slate-400 font-lemon">
                            {{ props.bundle.min_price /100 }} €
                        </span>
                        <span class="text-base font-medium font-lemon">
                            {{ props.bundle.min_discounted_price /100 }} €
                        </span>
                    </template>
    
                    <span v-else-if="props.bundle.min_price" class="text-base font-medium font-lemon">
                        {{ props.bundle.min_price /100 }} €
                    </span>
    
                    <template v-else-if="props.bundle.has_discounted_fixed_price">
                        <span class="mb-1 text-xs line-through text-slate-400 font-lemon">
                            {{ props.bundle.fixed_price }} €
                        </span>
                        <span class="text-base font-medium font-lemon">
                            {{ props.bundle.discounted_fixed_price }} €
                        </span>
                    </template>
    
                    <span v-else class="text-base font-medium font-lemon">
                        {{ props.bundle.fixed_price }} €
                    </span>
                </div>
            </div>
            <!-- / -->

            <div class="flex justify-between gap-2 mt-auto">
                <ShowAll @click="openModalBundle = true" text="Dettagli pacchetto" />
                <Button type="router" v-if="props.bundle.is_bookable" :href="route('reservation.create', props.bundle.id)" text="Prenota" icon="fa-solid fa-calendar-days" />
            </div>
        </div>
    </article>

    <Modal :isOpen="openModalBundle" @close="openModalBundle = false">
        <template #title>
            {{ props.bundle.name }}
        </template>
        <template #description>
            <div class="h-64 shrink-0 md:h-96">
                <img v-if="props.bundle.cover_path" :src="'/storage/' + props.bundle.cover_path" :alt="props.bundle.name" class="object-cover w-full h-full" />
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.bundle.name" class="object-contain w-full h-full p-14 bg-slate-800/40" />
            </div>
            
            <div class="flex flex-col gap-8 pt-6 grow">
                <!-- descrizione -->
                <div v-if="props.bundle.description" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Descrizione</h4>
                    <p class="m-0">
                        {{ props.bundle.description }}
                    </p>
                </div>
                <!-- / -->

                <!-- prezzi -->
                <div v-if="props.bundle.price_type !== 'no_price'" class="space-y-4">
                    <h4 class="pb-1 m-0 border-b border-orange-500">Prezzi</h4>

                    <div v-if="props.bundle.price_type === 'timebands_price'" class="grid grid-cols-1 gap-6 pt-4 sm:grid-cols-2">
                        <template v-for="wd, wdKey in props.weekdays">
                            <div v-if="props.bundle.prices.filter(price => price.timeband.weekday == wdKey).length" class="pb-4 space-y-4 border-b border-slate-700 last-of-type:border-0 sm:[&:nth-last-of-type(-n+2)]:border-0">
                                <h4 class="text-base text-white">
                                    {{ wd }}
                                </h4>
                                <div v-for="price in props.bundle.prices.filter(price => price.timeband.weekday == wdKey)" class="space-y-1 font-normal">
                                    <div>
                                        <div class="text-sm text-slate-100">
                                            Fascia oraria
                                            {{ price?.timeband.name }}
                                        </div>
                                        <div class="text-xs text-slate-300">
                                            {{ price.timeband.start }}
                                            -
                                            {{ price.timeband.end }}
                                        </div>
                                    </div>
                                    <div v-if="price.has_discounted_price" class="text-white">
                                        <span class="text-xs line-through text-slate-400">{{ price.price }} €</span>
                                        {{ price.discounted_price }} €
                                    </div>
                                    <div v-else class="text-white">
                                        {{ price.price }} €
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div v-else-if="props.bundle.price_type === 'fixed_price'">
                        <template v-if="props.bundle.discounted_fixed_price">
                            <span class="text-xs line-through text-slate-400 font-lemon">
                                {{ props.bundle.fixed_price }} €
                            </span>
                            <span class="text-base font-medium font-lemon">
                                {{ props.bundle.discounted_fixed_price }} €
                            </span>
                        </template>
    
                        <span v-else class="text-base font-medium font-lemon">
                            {{ props.bundle.discounted_fixed_price }} €
                        </span>
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
import Button from '@/Components/Form/Button.vue';

const props = defineProps({
    bundle: Object,
    weekdays: Object
});

const openModalBundle = ref(false);

</script>
