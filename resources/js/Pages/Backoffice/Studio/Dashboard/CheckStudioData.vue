<template>
    <p class="mb-4">
        Completa questi dati per rendere lo Studio pubblicabile.
    </p>
    <ul class="pl-0.5 list-none list-image-none">
        <li class="flex items-start gap-2">
            <i v-if="props.studio.name" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            il nome dello Studio
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.room_count || props.studio.bundle_count" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            almeno una Sala o un pacchetto pubblicato
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.count_close_days < 7" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            aperto almeno un giorno della settimana
        </li>
        <li v-if="props.studio.category === 'Professional'" class="flex items-start gap-2">
            <i v-if="props.studio.vat" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            la partita iva
        </li>
        <li class="flex items-start gap-2">
            <i v-if=" props.studio.description && props.studio.description.length > 100" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            la presentazione di almeno 100 caratteri, spazi esclusi
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.location.complete_address" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            la location
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.payment_methods_count" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            almeno un metodo di pagamento
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.photos_count >= 4" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            almeno quattro foto
        </li>
        <li class="flex items-start gap-2">
            <i v-if="props.studio.contacts.email || props.studio.contacts.phone || props.studio.contacts.telegram || props.studio.contacts.messenger || props.studio.contacts.whatsapp" class="w-4 mt-1 text-green-500 shrink-0 fa-solid fa-check" />
            <i v-else class="w-4 mt-1 text-red-500 shrink-0 fa-solid fa-xmark" />
            almeno un contatto
        </li>
    </ul>
</template>

<script setup>
const props = defineProps({
    studio: Object,
});

</script>
