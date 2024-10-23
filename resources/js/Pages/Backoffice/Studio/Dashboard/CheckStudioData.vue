<template>
    <p class="mb-4">
        Completa questi dati per rendere lo studio visibile e ricercabile.
    </p>
    <ul class="pl-0.5 list-none space-y-2">
        <li class="flex items-start gap-2">
            <i v-if="props.studio.name" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            il nome dello Studio
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.rooms.length || props.studio.bundles.length" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            almeno una Sala o un pacchetto
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.is_weekly_open" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            aperto almeno un giorno della settimana
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

<script setup>
const props = defineProps({
    studio: Object,
    is_weekly_open: Boolean,
});

</script>
