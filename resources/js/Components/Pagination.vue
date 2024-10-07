<template>
    <nav v-if="props.data.length && props.links.length > 3" class="flex flex-wrap font-normal justify-center w-full gap-1.5 mt-8">
        <template v-for="link in props.links">
            <div v-if="link.url === null" :title="title(link.label)" class="flex items-center justify-center text-xs transition-colors rounded-md cursor-not-allowed text-slate-500 bg-slate-800 w-7 h-7 text-grey-600 md:h-8 md:w-8 md:text-sm">
                <i v-if="link.label.includes('Previous')" class="text-xs fa-solid fa-chevron-left" />
                <i v-else-if="link.label.includes('Next')" class="text-xs fa-solid fa-chevron-right" />
                <template v-else>
                    {{ link.label }}
                </template>
            </div>

            <Link
                v-else
                :href="link.url"
                :title="title(link.label)"
                class="flex items-center justify-center text-xs text-white transition-colors rounded-md h-7 w-7 md:h-8 md:w-8 md:text-sm hover:bg-orange-500"
                :class="link.active ? 'bg-orange-500 font-medium': 'bg-slate-600'"
            >
                <i v-if="link.label.includes('Previous')" class="text-xs fa-solid fa-chevron-left" />
                <i v-else-if="link.label.includes('Next')" class="text-xs fa-solid fa-chevron-right" />
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
    if(label.includes('Previous')){
        return 'Precedente';
    }

    if(label.includes('Next')){
        return 'Successivo';
    }
};

</script>
