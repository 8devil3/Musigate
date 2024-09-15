<template>
    <div class="grid grid-cols-5 leading-none text-center bg-gray-900 shadow lg:hidden shrink-0 whitespace-nowrap">
        <Link :href="route('dashboard')" class="border-t-2 flex flex-col font-extralight items-center w-full gap-1.5 py-2 px-4 text-[10px] bg-gray-900" :class="route().current('dashboard') ? 'border-orange-500 text-orange-500' : 'border-gray-800'">
            <i class="text-sm fa-solid fa-home"></i>
            Dashboard
        </Link>
        <Link :href="route('studio.links')" class="border-t-2 flex flex-col font-extralight items-center w-full gap-1.5 py-2 px-4 text-[10px] bg-gray-900" :class="route().current('studio.links') || route().current('studio.*') ? 'border-orange-500 text-orange-500' : 'border-gray-800'">
            <i class="text-sm fa-solid fa-record-vinyl"></i>
            Studio
        </Link>
        <Link :href="route('rooms.index')" class="border-t-2 flex flex-col font-extralight items-center w-full gap-1.5 py-2 px-4 text-[10px] bg-gray-900" :class="route().current('rooms.*') ? 'border-orange-500 text-orange-500' : 'border-gray-800'">
            <i class="text-sm fa-solid fa-microphone-lines"></i>
            Sale Studio
        </Link>
        <Link :href="route('account.edit')" class="border-t-2 flex flex-col font-extralight items-center w-full gap-1.5 py-2 px-4 text-[10px] bg-gray-900" :class="route().current('account.*') ? 'border-orange-500 text-orange-500' : 'border-gray-800'">
            <i class="text-sm fa-solid fa-user-gear"></i>
            Account
        </Link>
        <Link :href="route('suggestions.create')" class="border-t-2 flex flex-col font-extralight items-center w-full gap-1.5 py-2 px-4 text-[10px] bg-gray-900" :class="route().current('suggestions.*') ? 'border-orange-500 text-orange-500' : 'border-gray-800'">
            <i class="text-sm fa-solid fa-flag"></i>
            Segnalazioni
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

</script>
