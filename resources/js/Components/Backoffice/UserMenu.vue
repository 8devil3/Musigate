<template>
    <Menu as="nav" class="relative">
        <MenuButton class="relative flex items-center gap-4 focus:outline-none focus:ring-0">
            <div class="flex-col hidden gap-1 text-right md:flex">
                <span class="font-sans text-sm font-medium leading-none uppercase">Accesso come</span>
                <span class="font-sans text-sm leading-none text-slate-300">{{ usePage().props.auth.user.email }}</span>
            </div>
            <Avatar :first_name="usePage().props.auth.user.first_name" :last_name="usePage().props.auth.user.last_name" :img="usePage().props.auth.studio.logo"/>
            <div class="absolute flex items-center justify-center -bottom-0.5 -right-0.5 bg-orange-500 border border-orange-500 h-4 w-4 rounded-full">
                <i class="text-[8px] leading-none text-white fa-solid fa-chevron-down" />
            </div>
        </MenuButton>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems as="ul" class="absolute right-0 z-50 py-1 mt-2 overflow-hidden origin-top-right border rounded-lg shadow-lg bg-slate-800 border-slate-600 ring-0 focus:outline-none">
                <MenuItem as="li" class="hover:bg-orange-500 whitespace-nowrap hover:text-white">
                    <Link :href="route('dashboard')" class="flex items-center gap-2 px-4 py-2 text-sm">
                        <i class="w-5 fa-solid fa-home" />
                        Dashboard
                    </Link>
                </MenuItem>

                <MenuItem as="li" class="hover:bg-orange-500 whitespace-nowrap hover:text-white">
                    <Link :href="route('account.edit')" class="flex items-center gap-2 px-4 py-2 text-sm">
                        <i class="w-5 fa-solid fa-user-gear" />
                        Account
                    </Link>
                </MenuItem>

                <hr class="h-0 mx-3 my-1 border-t border-slate-400" />
                
                <MenuItem as="li" class="hover:bg-orange-500 whitespace-nowrap hover:text-white">
                    <form @submit.prevent="router.post(route('logout'))">
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm">
                            <i class="w-5 fa-solid fa-right-from-bracket" />
                            Esci
                        </button>
                    </form>
                </MenuItem>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import Avatar from '@/Components/Avatar.vue';

</script>
