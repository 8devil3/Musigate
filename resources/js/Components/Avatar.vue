<template>
    <div class="flex items-center justify-center overflow-hidden text-center border-2 border-orange-500 rounded-full size-9 lg:size-10 bg-slate-900 shrink-0">
        <svg v-if="!props.first_name && !props.last_name" class="h-6 md:h-7 md:pt-0.5" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="6" cy="3" r="3" fill="white"/>
            <path d="M0 10C0.857143 11.7143 3.50003 13 6.00003 13C8.50002 13 11.1429 11.7143 12 10C11.1429 8.28571 7.99003 7 6.00003 7C4.00002 7 0.857143 8.28571 0 10Z" fill="white"/>
        </svg>

        <div v-else-if="!props.img" class="flex items-center justify-center w-full h-full overflow-hidden text-sm leading-none tracking-wider text-white uppercase rounded-full bg-slate-900 lg:text-base font-lemon">
            {{ props.first_name.charAt(0) }}{{ props.last_name.charAt(0) }}
        </div>

        <img v-else :src="props.img.includes('http') ? props.img : '/storage/' + props.img" alt="avatar" class="object-cover w-full h-full"/>
    </div>
</template>

<script setup>
const props = defineProps({
    first_name: {
        type: String,
        default: null
    },
    last_name: {
        type: String,
        default: null
    },
    img: {
        type: String,
        default: null
    }
})

</script>
