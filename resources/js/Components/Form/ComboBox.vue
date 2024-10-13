<template>
    <div class="relative space-y-2">
        <Label v-if="props.label" for="search-location" :label="props.label" />
        <Input v-model="query" :placeholder="props.placeholder" @clear="clearInput()" :icon="props.inputIcon" :disabled="props.disabled" />

        <Transition name="slide-fade">
            <div v-show="filteredItems.length && showList" class="absolute inset-x-0 z-40 h-48 overflow-hidden text-sm origin-top-right border rounded-lg shadow-lg bg-slate-800 border-slate-600 ring-1 ring-orange-500 ring-opacity-5 focus:outline-none">
                <ul class="h-full p-0 m-0 mt-1 overflow-y-auto list-none list-image-none">
                    <li v-for="item in filteredItems">
                        <button type="button" @click="selectItem(item)" class="w-full px-4 py-2 text-left truncate transition-colors cursor-pointer text-slate-200 hover:bg-orange-500 hover:text-white group ui-active:bg-orange-500 ui-active:text-white">
                            <i v-if="props.listIcon" class="mr-1 text-xs" :class="props.listIcon" />
                            {{ item }}
                        </button>
                    </li>
                </ul>
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

const query = ref('');
const vModel = defineModel({default: null});
const showList = ref(false);
const classes = "text-left w-full h-8 px-4 pl-8 py-0 text-sm text-white bg-slate-800/50 border border-slate-400 rounded-full placeholder:text-slate-300/80 disabled:bg-slate-800 placeholder:truncate truncate disabled:cursor-not-allowed disabled:text-slate-500 disabled:border-slate-500 font-sans placeholder:font-light font-light focus:ring-orange-500/50 focus:border-orange-500 focus:border-orange-500 focus:shadow-md focus:shadow-orange-500";

const filteredItems = computed(()=>{
    if(query.value && query.value.length >= 2) {
        showList.value = true;
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
    emit('selected', item);
    vModel.value = item;
    showList.value = false;
};

const clearInput = ()=>{
    emit('clear');
    vModel.value = null;
    showList.value = false;
}

//click outside
onBeforeMount(()=>{
    document.addEventListener('click', ()=> showList.value = false);
});

onUnmounted(()=>{
    document.removeEventListener('click', ()=> showList.value = false);
});

</script>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.2s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    @apply opacity-0 translate-y-10;
}

</style>
