<template>
    <div class="flex flex-wrap justify-center w-full gap-1 pt-6 mt-12 border-t border-orange-500 font-lemon">
        <!-- prima pagina -->
        <div v-if="props.pagination.current_page === 1" title="Primo" class="flex items-center justify-center w-8 h-8 text-center text-gray-300 transition-colors rounded-full">
            <i class="fa-solid fa-angles-left"></i>
        </div>

        <Link v-else :href="props.pagination.first_page_url" title="Primo" class="flex items-center justify-center w-8 h-8 text-center transition-colors rounded-full hover:text-white hover:bg-orange-500">
            <i class="fa-solid fa-angles-left"></i>
        </Link>
        <!-- / -->

        <template v-for="link, id in props.pagination.links">
            <!-- precedente -->
            <template v-if="id === 0">
                <div v-if="props.pagination.current_page === 1" title="Precedente" class="flex items-center justify-center w-8 h-8 text-center text-gray-300 transition-colors rounded-full">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>

                <Link v-else :href="props.pagination.first_page_url" title="Precedente" class="flex items-center justify-center w-8 h-8 text-center transition-colors rounded-full hover:text-white hover:bg-orange-500">
                    <i class="fa-solid fa-chevron-left"></i>
                </Link>
            </template>
            <!-- / -->

            <!-- successiva -->
            <template v-else-if="id === props.pagination.links.length -1">
                <div v-if="props.pagination.current_page === props.pagination.last_page" title="Successivo" class="flex items-center justify-center w-8 h-8 text-center text-gray-300 transition-colors rounded-full">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>

                <Link v-else :href="props.pagination.last_page_url" title="Successivo" class="flex items-center justify-center w-8 h-8 text-center transition-colors rounded-full hover:text-white hover:bg-orange-500">
                    <i class="fa-solid fa-chevron-right"></i>
                </Link>
            </template>
            <!-- / -->

            <!-- pagine numerate -->
            <template v-else>
                <div v-if="link.active" :title="link.label" class="flex items-center justify-center w-8 h-8 text-center text-orange-500 border-2 border-orange-500 rounded-full bg-orange-500/10">
                    {{ link.label }}
                </div>

                <Link v-else :href="link.url" :title="link.label" class="flex items-center justify-center w-8 h-8 text-center transition-colors rounded-full hover:text-white hover:bg-orange-500">
                    {{ link.label }}
                </Link>
            </template>
            <!-- / -->
        </template>


        <!-- ultima pagina -->
        <div v-if="props.pagination.current_page === props.pagination.last_page" title="Ultimo" class="flex items-center justify-center w-8 h-8 text-center text-gray-300 transition-colors rounded-full">
            <i class="fa-solid fa-angles-right"></i>
        </div>
        
        <Link v-else :href="props.pagination.last_page_url" title="Ultimo" class="flex items-center justify-center w-8 h-8 text-center transition-colors rounded-full hover:text-white hover:bg-orange-500">
            <i class="fa-solid fa-angles-right"></i>
        </Link>
        <!-- / -->
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    pagination: Object
});

</script>
