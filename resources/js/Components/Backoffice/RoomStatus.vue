<template>
    <div class="inline-flex items-stretch overflow-hidden border rounded-full w-fit" :class="classes.style" :title="props.statusName">
        <div class="flex items-center justify-center size-5 bg-slate-800 rounded-full shadow-inner shrink-0">
            <i class="text-xs leading-none" :class="classes.icon"></i>
        </div>

        <div class="flex items-center justify-center px-4 py-1 font-sans text-xs font-medium leading-none text-center text-white grow whitespace-nowrap">
            {{ props.statusName }}
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    statusId: Number,
    statusName: String
})

const classes = computed(()=>{
    let classes = {
        icon: null,
        style: null,
    }

    switch (props.statusId) {
        case 1: //respinta
            classes.icon = 'fa-solid fa-triangle-exclamation text-orange-700';
            classes.style = 'border-orange-700 bg-orange-700/50';
        break;
            
        case 2: //sospesa
            classes.icon = 'fa-solid fa-pause text-zinc-400';
            classes.style = 'border-zinc-500 bg-zinc-500/50';
        break;

        case 3: //in approvazione
            classes.icon = 'fa-regular fa-circle text-amber-500';
            classes.style = 'border-amber-500 bg-amber-500/50';
        break;

        case 4: //pubblicata
            classes.icon = 'fa-solid fa-check text-emerald-500';
            classes.style = 'border-emerald-500 bg-emerald-500/50';
        break;

        case 5: //nuova
            classes.icon = 'fa-solid fa-certificate text-blue-500';
            classes.style = 'border-blue-500 bg-blue-500/50';
        break;
    }

    return classes;
});

// const status = {
    // 'Respinta',
    // 'Sospesa',
    // 'In approvazione',
    // 'Pubblicata',
    // 'Nuova',
// }

</script>
