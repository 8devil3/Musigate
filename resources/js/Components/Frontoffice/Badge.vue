<template>
    <div class="inline-flex items-center justify-center text-center text-white tracking-wide font-sans text-xs capitalize whitespace-nowrap px-3 py-0.5 rounded-full" :class="data.colorClasses">
        <i v-if="props.icon" :class="`${props.icon} ${props.text ? 'mr-2': ''} text-xs`"></i>
        {{ props.text}}
    </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'orange'
    },
    icon: {
        type: String,
        default: null
    },
    text: {
        type: String,
        default: null
    }
});

const data = reactive({
    colorClasses: null,
})

onMounted(()=>{
    setColorClasses();
})

const setColorClasses = () => {
    switch (props.color) {
        case 'fuchsia':
            data.colorClasses = 'border border-fuchsia-400 bg-fuchsia-500/30';
            break;
        case 'indigo':
            data.colorClasses = 'border border-indigo-400 bg-indigo-500/30';
            break;
        case 'blue':
            data.colorClasses = 'border border-blue-400 bg-blue-500/30';
            break;
        case 'sky':
            data.colorClasses = 'border border-sky-400 bg-sky-500/30';
            break;
        case 'green':
            data.colorClasses = 'border border-green-400 bg-green-500/30';
            break;
        case 'yellow':
            data.colorClasses = 'border border-yellow-400 bg-yellow-500/30';
            break;
        case 'gray':
            data.colorClasses = 'border border-gray-400 bg-gray-500/30';
            break;
        case 'red':
            data.colorClasses = 'border border-red-400 bg-red-500/30';
            break;
        case 'orange':
            data.colorClasses = 'border border-orange-400 bg-orange-500/30';
            break;
    }
}
</script>
