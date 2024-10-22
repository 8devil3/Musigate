<template>
    <div ref="comboboxElement" class="relative space-y-1">
        <Input v-model="query" :placeholder="props.placeholder" @focus="showItemsList" @clear="clearInput()" :label="props.label" :icon="props.inputIcon" :disabled="props.disabled" />

        <Transition name="slide-fade">
            <div v-show="showList" ref="listElement" class="absolute inset-x-0 z-40 h-48 overflow-hidden text-sm origin-top-right border rounded-xl bg-slate-900 border-slate-600 ring-1 ring-orange-500 ring-opacity-5 focus:outline-none">
                <ul v-if="filteredItems.length" class="h-full p-0 m-0 mt-1 overflow-y-auto list-none shadow-lg list-image-none">
                    <li v-for="item in filteredItems">
                        <button type="button" @click="selectItem(item)" class="w-full px-4 py-2 text-left truncate transition-colors cursor-pointer text-slate-200 hover:bg-orange-500 hover:text-white group ui-active:bg-orange-500 ui-active:text-white">
                            <i v-if="props.listIcon" class="mr-1 text-xs" :class="props.listIcon" />
                            {{ item }}
                        </button>
                    </li>
                </ul>

                <div v-else class="px-4 py-2 text-slate-400">
                    Nessuna provincia trovata
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onBeforeMount, onUnmounted } from "vue";
import Input from '@/Components/Form/Input.vue';
import Label from "@/Components/Form/Label.vue";

const props = defineProps({
    options: {
        type: Array,
        default: []
    },
    label: {
        type: String,
        default: null
    },
    inputIcon: {
        type: String,
        default: null
    },
    listIcon: {
        type: String,
        default: null
    },
    placeholder: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['selected', 'clear']);

const query = defineModel({default: null});
const comboboxElement = ref(null);
const listElement = ref(null);
const showList = ref(false);
const classes = "text-left w-full h-8 px-4 pl-8 py-0 text-sm text-white bg-slate-800/50 border border-slate-400 rounded-full placeholder:text-slate-300/80 disabled:bg-slate-800 placeholder:truncate truncate disabled:cursor-not-allowed disabled:text-slate-500 disabled:border-slate-500 font-sans placeholder:font-light font-light focus:ring-orange-500/50 focus:border-orange-500 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500";

const filteredItems = computed(()=>{
    if(query.value && query.value.length >= 2) {
        return props.options.filter((item) => {
            let strRegEx =`^${query.value.trim()}.*`;
            let newRegEx = new RegExp(strRegEx, 'gi');
            return item.toLowerCase().match(newRegEx);
        }).sort((a,b) => a.localeCompare(b, 'it'));
    } else {
        return [];
    }
});

const selectItem = (item)=>{
    query.value = item;
    showList.value = false;
    emit('selected', item);
};

const clearInput = ()=>{
    query.value = '';
    showList.value = false;
    emit('clear');
}

//click outside
const handleClickOutside = (event) => {
    // Verifichiamo se il clic è avvenuto fuori dall'input e dalla lista
    if (comboboxElement.value && !comboboxElement.value.contains(event.target)) {
        showList.value = false;
    }
};

const handleEscapeKey = (e) => {
    if(e.key === 'Escape') {
        showList.value = false;
    }
};

const showItemsList = ()=>{
    showList.value = true;
    listElement.value.scrollIntoView({ behavior: 'smooth', block: 'start' });

    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keyup', handleEscapeKey);
};

onUnmounted(()=>{
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keyup', handleEscapeKey);
});

</script>
