<template>
    <ContentLayout
        as="div"
        title="Dashboard"
        icon="fa-solid fa-home"
    >
        <template #content>
            <div class="space-y-6 md:space-y-8">
                <h1 class="py-4 m-0 text-4xl font-bold text-center">
                    Ciao
                    {{ usePage().props.auth.user.first_name }}
                </h1>
    
                <!-- count box -->
                <div class="grid max-w-xl grid-cols-1 gap-4 mx-auto sm:grid-cols-2">
                    <InfoBlock
                        v-if="props.studio.is_visible"
                        title="Lo Studio è visibile"
                        color="success"
                        icon="fa-solid fa-check"
                        class="col-span-full"
                    >
                        Lo Studio attualmente è visibile e ricercabile pubblicamente dagli utenti
                    </InfoBlock>

                    <InfoBlock
                        v-else
                        title="Lo Studio non è visibile"
                        color="danger"
                        icon="fa-solid fa-xmark"
                        class="col-span-full"
                    >
                        Lo Studio attualmente non è visibile nè ricercabile pubblicamente dagli utenti
                    </InfoBlock>

                    <CountCard
                        v-if="!props.studio.is_complete"
                        title="Completamento dati Studio"
                        :icon="props.studio.is_complete ? 'fa-regular fa-circle-check' : 'fa-regular fa-circle-xmark'"
                        :iconColor="props.studio.is_complete ? 'text-green-500' : 'text-red-500'"
                        class="col-span-full"
                    >
                        <template #content>
                            <CheckStudioData :studio="props.studio" />
                        </template>
                    </CountCard>

                    <CountCard
                        title="Sale visibili"
                        icon="fa-solid fa-microphone-lines"
                        :count="props.studio.rooms.filter(room => room.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai alle Sale"
                    />

                    <CountCard
                        title="Sale non visibili"
                        icon="fa-solid fa-microphone-lines-slash"
                        :count="props.studio.rooms.filter(room => !room.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai alle Sale"
                    />

                    <CountCard
                        title="Pacchetti visibili"
                        icon="fa-solid fa-store"
                        :count="props.studio.bundles.filter(bundle => bundle.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai ai pacchetti"
                    />

                    <CountCard
                        title="Pacchetti non visibili"
                        icon="fa-solid fa-store-slash"
                        :count="props.studio.bundles.filter(bundle => !bundle.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai ai pacchetti"
                    />


                </div>
                <!-- / -->    
            </div>
        </template>
    </ContentLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import CountCard from './CountCard.vue';
import CheckStudioData from './CheckStudioData.vue';

const props = defineProps({
    studio: Object
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';
import InfoBlock from '@/Components/InfoBlock.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Dashboard',
    }, {default: () => page}),
};
</script>
