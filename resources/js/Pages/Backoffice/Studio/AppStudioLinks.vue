<template>
    <ContentLayout
        as="div"
        title="Studio"
        icon="fa-solid fa-record-vinyl"
    >
        <template #content>
            <ul class="grid grid-cols-2 gap-3 mt-4 text-center list-none sm:grid-cols-3 list-image-none">
                <li v-for="child in menuItems.studio.find(item => item.route === 'studio.links').children">
                    <Link :href="route(child.route)" class="flex flex-col justify-center h-full p-4 space-y-1 text-sm text-center text-white transition-colors bg-transparent border-2 border-orange-500 rounded-full cursor-pointer bg-slate-900 hover:border-orange-500 hover:text-white focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0">
                        <i class="text-lg" :class="child.icon" />
                        <div>
                            {{ child.label }}
                        </div>
                    </Link>
                </li>
            </ul>
        </template>
    </ContentLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import menuItems from '@/Components/Backoffice/menuItems.json';

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Disponibilità settimanale',
    }, {default: () => page}),
};
</script>