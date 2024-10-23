<template>   
    <div class="flex flex-col gap-1" :id="props.id">
        <div v-if="props.label" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
            {{ props.label }}
            <span v-show="props.required" class="text-sm text-red-600">*</span>
        </div>

        <div class="relative w-full">
            <button
                @click.stop="isOpen = !isOpen"
                type="button"
                class="flex items-center justify-between w-full h-8 py-0 pl-2 pr-3 text-sm leading-tight text-left text-black bg-white border border-slate-300 rounded-md text-ellipsis disabled:text-slate-300 focus:ring-emerald-600/50 focus:border-emerald-600 focus:shadow-md"
                :class="{'text-red-500 border-red-600' : props.error}"
            >
                <span v-if="!vModel" class="text-slate-400">
                    {{ props.default }}
                </span>
                
                <span v-else class="text-black">
                    {{ props.options[vModel] }}
                </span>

                <input type="text" :required="props.required" class="absolute inset-0 p-0 m-0 border-0 opacity-0 -z-40" :value="vModel"/>
                
                <i class="text-[11px] text-slate-500 fa-solid fa-chevron-down"></i>
            </button>
            
            <ul v-if="isOpen" class="absolute z-40 flex flex-col w-full mt-1 overflow-x-hidden overflow-y-auto bg-white border border-slate-400 rounded-lg shadow-lg max-h-60 focus:outline-none">
                <li v-for="option, key in props.options" @click="vModel = parseInt(key), isOpen = false" class="px-4 py-2 text-black transition-colors cursor-pointer hover:bg-emerald-600 hover:text-white">
                    <div class="text-sm">
                        {{ option }}
                    </div>
                    <div class="text-xs">
                        {{ props.definitions[key] }}
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- overlay per click chiusura calendario -->
        <div v-if="isOpen" @click="isOpen = false" class="absolute inset-0 border-0"></div>
        <!-- / -->

        <FieldError :error="props.error"/>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import FieldError from '@/Components/Form/FieldError.vue';

const props = defineProps({
    options: Object,
    definitions: Object,
    default: String,
    id: String,
    error: String,
    required: Boolean,
    label: String
});

const isOpen = ref(false);

const vModel = defineModel();

</script>
