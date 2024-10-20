<template>
    <ContentLayout
        title="Dashboard"
        icon="fa-solid fa-home"
        as="div"
    >
        <template #content>
            <div class="flex flex-col gap-6 p-4 md:gap-8 md:p-6 md:rounded-t-md">
                <h1 class="py-4 m-0 text-4xl font-bold text-center">
                    Ciao
                    {{ usePage().props.auth.user.first_name }}
                </h1>
    
                <!-- count box -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="p-4 space-y-4 bg-gray-900 border-2 border-orange-600 rounded-xl">
                        <h3 class="flex items-start gap-2 text-lg">
                            <i class="mt-1.5 fa-regular fa-circle-play" />
                            Sale Studio pubblicate
                        </h3>
    
                        <div class="text-4xl font-medium">{{ props.studio.rooms?.filter(room => room.status === 'pubblicata').length ?? 0 }}</div>
                        <Link :href="route('sale.index')" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Vai alle Sale Studio <i class="ml-1 fa-solid fa-arrow-right" /></Link>
                    </div>
    
                    <div class="p-4 space-y-4 bg-gray-900 border-2 border-orange-600 rounded-xl">
                        <h3 class="flex items-start gap-2 text-lg">
                            <i class="mt-1.5 fa-regular fa-circle-pause" />
                            Sale Studio sospese
                        </h3>
    
                        <div class="text-4xl font-medium">{{ props.studio.rooms?.filter(room => room.status === 'sospesa').length ?? 0 }}</div>
                        <Link :href="route('sale.index')" class="font-medium text-orange-500 transition-colors hover:text-orange-400">Vai alle Sale Studio <i class="ml-1 fa-solid fa-arrow-right" /></Link>
                    </div>
    
                    <div class="p-4 space-y-4 bg-gray-900 border-2 border-orange-600 rounded-xl">
                        <h3 class="flex items-start gap-2 text-lg">
                            <i class="mt-1.5 fa-regular" :class="props.studio.is_complete ? ' fa-circle-check text-green-500' : 'fa-circle-xmark text-red-500'" />
                            Completamento dati Studio
                        </h3>
                        
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
                    </div>
                </div>
                <!-- / -->    
            </div>
        </template>
    </ContentLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

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
