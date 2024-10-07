<template>
    <div>
        <Label for="search-location" :label="props.label" />
        <Combobox as="div" id="search-location" v-model="vModel" class="relative font-sans">
            <ComboboxInput
                type="text"
                placeholder="Digita una località" 
                :displayValue="(location) => location?.comune"
                @input="query = $event.target.value"
                @change="emit('change')"
                :class="[classes, 'pl-8']"
                autocomplete="off"
                :required="props.required"
            />

            <div class="absolute inset-y-0 flex items-center left-4">
                <i class="mr-1 text-xs text-orange-500 fa-solid fa-location-dot" />
            </div>

            <button v-show="vModel" @click="clear()" type="button" class="absolute inset-y-0 flex items-center text-xs text-slate-400 right-3">
                <i class="fa-solid fa-circle-xmark" />
            </button>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <ComboboxOptions as="div" class="absolute inset-x-0 z-40 h-64 mt-2 overflow-hidden text-sm origin-top-right border rounded-lg shadow-lg bg-slate-800 border-slate-600 ring-1 ring-orange-500 ring-opacity-5 focus:outline-none">
                    <ul v-if="filterLocations.length" class="h-full overflow-y-auto">
                        <ComboboxOption as="li" v-for="location, id in filterLimited" :key="id" :value="location" class="w-full px-4 py-2 truncate transition-colors cursor-pointer text-slate-200 hover:bg-orange-500 hover:text-white group ui-active:bg-orange-500 ui-active:text-white">
                            <div class="flex items-start gap-2">
                                <i class="text-xs fa-solid fa-location-dot mt-0.5" />
                                <div class="font-normal">
                                    {{ location.comune }}
                                    <div class="text-xs font-light text-slate-400 group-hover:text-white">
                                        Provincia di {{ location.provincia }}
                                    </div>
                                </div>
                            </div>
                        </ComboboxOption>
                        
                        <button type="button" v-if="arrayLimit < filterLocations.length" @click="loadMore()" class="flex items-center w-full gap-2 px-4 py-2 italic text-orange-500 truncate transition-colors hover:bg-orange-500 hover:text-white">
                            ...carica altre località
                        </button>
                    </ul>

                    <ComboboxOption as="li" value="all" class="flex items-center w-full gap-2 px-4 py-2 truncate transition-colors cursor-pointer text-slate-300 hover:bg-orange-500 hover:text-white ui-active:bg-orange-500 ui-active:text-white">
                        Nessuna località trovata
                    </ComboboxOption>
                </ComboboxOptions>
            </transition>
        </Combobox>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import locations from './locations.json';
import Label from "@/Components/Form/Label.vue";
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxOption } from '@headlessui/vue';

const props = defineProps({
    label: String,
    required: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['change']);

const classes = "text-left w-full h-8 px-4 py-0 text-sm text-white bg-slate-800/50 border border-slate-400 rounded-full placeholder:text-slate-300/80 disabled:bg-slate-800 placeholder:truncate truncate disabled:cursor-not-allowed disabled:text-slate-500 disabled:border-slate-500 font-sans placeholder:font-light font-light focus:ring-orange-500/50 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500";

const query = ref('');
const vModel = defineModel({default: null});

//limito i risultati del filtro per non appesantire la pagina e i caricamenti
const arrayLimit = ref(20); // Initial limit

const filterLocations = computed(()=>{
    if(query.value.length >= 2) {
        return locations.filter((location) => {
            let strRegEx =`^${query.value.trim()}.*`;
            let newRegEx = new RegExp(strRegEx, 'gi');
            return location.comune.toLowerCase().match(newRegEx);
        }).sort((a,b) => a.comune.localeCompare(b.comune, 'it'));
    }
})

//Limito i risultati
const filterLimited = computed(()=>{
    return filterLocations.value.slice(0, arrayLimit.value);
});

//Carica altri risultati
const loadMore = ()=>{
    arrayLimit.value += 20; // Increase the limit by a certain number
};

const clear = ()=>{
    query.value = '';
    vModel.value = null;
}

</script>
