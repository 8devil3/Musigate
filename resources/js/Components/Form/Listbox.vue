<template>   
    <div class="flex flex-col gap-1" :id="props.id">
        <div v-if="props.label" class="px-1 text-xs font-medium leading-none whitespace-nowrap">
            {{ props.label }}
            <span v-show="props.required" class="text-sm text-red-600">*</span>
        </div>

        <div class="relative w-full">
            <button
                @click.stop="isOpen = !isOpen"
                type="button"
                class="flex items-center justify-between w-full h-8 py-0 pl-2 pr-3 text-sm leading-tight text-left bg-slate-900 border border-slate-400 rounded-md text-ellipsis placeholder:text-slate-400 disabled:text-slate-300 focus:ring-emerald-600/50 focus:border-orange-500 focus:shadow-md"
                :class="{'text-slate-400' : !selected.length, 'text-red-500 border-red-600' : props.error}"
            >
                {{ selected.length ? selected.length + ' selected option(s)' : '--Select option(s)--' }}

                <input type="text" :required="props.required" class="absolute inset-0 p-0 m-0 border-0 opacity-0 -z-40" :value="selected"/>
                
                <i class="text-[11px] text-slate-500 fa-solid fa-chevron-down"></i>
            </button>
            
            <ul v-if="isOpen" class="absolute z-40 flex flex-col w-full gap-4 p-4 mt-1 overflow-x-hidden overflow-y-auto bg-white border border-slate-400 rounded-lg shadow-lg max-h-60 focus:outline-none">
                <li v-for="option, key in props.options" class="flex items-center gap-1.5">
                    <input
                        :id="props.id + '-' + option"
                        type="checkbox"
                        v-model="vModel"
                        :value="parseInt(key)"
                        class="size-4 rounded-[4px] border-2 border-emerald-600 bg-transparent text-emerald-600 cursor-pointer"
                    >
                    <label :for="props.id + '-' + option" class="text-xs cursor-pointer md:text-sm">
                        {{ option }}
                    </label>
                </li>
            </ul>
        </div>
        
        <!-- overlay per click chiusura calendario -->
        <div v-if="isOpen" @click="isOpen = false" class="absolute inset-0 border-0"></div>
        <!-- / -->

        <div v-show="props.error" class="px-2 text-sm text-left text-red-600">
            {{ props.error }}
        </div>

        <div class="px-2">
            <span v-for="option, id in selected" class="mt-1 text-xs">{{ props.options[option] }}<template v-if="id !== selected.length -1 && selected">, </template></span>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: []
    },
    options: Object,
    id: String,
    error: String,
    required: Boolean,
    label: String
});

const vModel = defineModel();

const selected = ref(vModel.value);
const isOpen = ref(false);

</script>
