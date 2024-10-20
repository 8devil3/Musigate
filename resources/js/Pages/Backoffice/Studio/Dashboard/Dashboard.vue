<template>
    <ContentLayout
        as="div"
        title="Dashboard"
        icon="fa-solid fa-home"
    >
        <template #content>
            <div class="max-w-4xl p-4 mx-auto space-y-6 md:p-6 md:space-y-8">
                <h1 class="py-4 m-0 text-4xl font-bold text-center">
                    Ciao
                    {{ usePage().props.auth.user.first_name }}
                </h1>
    
                <!-- count box -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <CountCard
                        title="Sale attive"
                        icon="fa-solid fa-microphone-lines"
                        :count="props.studio.rooms.filter(room => room.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai alle Sale"
                    />

                    <CountCard
                        title="Sale disattive"
                        icon="fa-solid fa-microphone-lines-slash"
                        :count="props.studio.rooms.filter(room => !room.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai alle Sale"
                    />

                    <CountCard
                        title="Pacchetti"
                        icon="fa-solid fa-box-archive"
                        :count="props.studio.rooms.filter(room => !room.is_visible).length ?? 0"
                        routeName="sale.index"
                        routeLabel="Vai ai pacchetti"
                    />

                    <CountCard
                        title="Completamento dati Studio"
                        :icon="props.studio.is_complete ? 'fa-regular fa-circle-check' : 'fa-regular fa-circle-xmark'"
                        :iconColor="props.studio.is_complete ? 'text-green-500' : 'text-red-500'"
                        class="col-span-full"
                    >
                        <template #content>
                            <ul class="pl-0.5 list-none space-y-2">
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.name" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    il nome dello Studio
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.category" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    la categoria
                                </li>
                                <li v-if="props.studio.category === 'Professional'" class="flex items-start gap-2">
                                    <i v-if="props.studio.vat" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    la partita iva
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.description?.length > 100" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    la presentazione di almeno 100 caratteri, spazi esclusi
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.location.complete_address" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    la location
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.payment_methods?.length" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    almeno un metodo di pagamento
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.photos?.length" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    almeno una foto
                                </li>
                                <li class="flex items-start gap-2">
                                    <i v-if="props.studio.contacts?.email || props.studio.contacts?.phone || props.studio.contacts?.telegram || props.studio.contacts?.messenger || props.studio.contacts?.whatsapp" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
                                    <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
                                    almeno un contatto
                                </li>
                            </ul>
                        </template>
                    </CountCard>
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

const props = defineProps({
    studio: Object
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Dashboard',
    }, {default: () => page}),
};
</script>
