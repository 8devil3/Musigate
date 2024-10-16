<template>
    <nav v-if="props.data.length && props.links.length > 3" class="flex flex-wrap font-normal justify-center w-full gap-1.5 mt-8">
        <template v-for="link in props.links">
            <div v-if="link.url === null" :title="title(link.label)" class="flex items-center justify-center text-xs transition-colors rounded-full cursor-not-allowed text-slate-600 w-7 h-7 text-grey-600 md:h-8 md:w-8 md:text-sm">
                <i v-if="link.label.includes('Precedente')" class="text-xs fa-solid fa-chevron-left" />
                <i v-else-if="link.label.includes('Successivo')" class="text-xs fa-solid fa-chevron-right" />
                <template v-else>
                    {{ link.label }}
                </template>
            </div>

            <Link
                v-else
                :href="link.url"
                :title="title(link.label)"
                class="flex items-center justify-center text-xs text-white transition-colors border-2 border-transparent rounded-full hover:border-orange-500 h-7 w-7 md:h-8 md:w-8 md:text-sm"
                :class="link.active && 'font-medium bg-orange-500'"
            >
                <i v-if="link.label.includes('Precedente')" class="text-xs fa-solid fa-chevron-left" />
                <i v-else-if="link.label.includes('Successivo')" class="text-xs fa-solid fa-chevron-right" />
                <template v-else>
                    {{ link.label }}
                </template>
            </Link>
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    data: Object,
    links: Object,
});

const title = (label)=>{
    if(label.includes('Precedente')){
        return 'Precedente';
    }

    if(label.includes('Successivo')){
        return 'Successivo';
    }
};

</script>
