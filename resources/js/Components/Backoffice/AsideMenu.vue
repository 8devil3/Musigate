<template>
    <nav class="flex flex-col h-full font-sans font-medium max-w-64 bg-slate-900 shrink-0 noscrollbar">
        <div class="sticky top-0 flex flex-col h-16 shrink-0 bg-slate-900">
            <Link :href="route('dashboard')" class="flex items-center justify-center w-64 grow">
                <img src="/img/logo/logo_horizontal_complete.svg" alt="Musigate logo" class="h-8">
            </Link>
            <div class="shrink-0 block h-0.5 bg-gradient-to-r from-transparent via-orange-500 to-transparent-500" />
        </div>

        <div class="py-6 space-y-6 overflow-y-auto grow">
            <!-- nome e logo studio -->
            <div class="px-2 space-y-4 shrink-0">
                <div class="flex items-center justify-center w-16 mx-auto border-2 border-orange-500 rounded-full aspect-square">
                    <img v-if="usePage().props.auth.studio.logo" :src="'/storage/' + usePage().props.auth.studio.logo" :alt="usePage().props.auth.studio.name" class="object-contain w-full h-full rounded-full overflow-clip">
                    <img v-else src="/img/logo/logo_placeholder.svg" :alt="usePage().props.auth.studio.name" class="object-contain w-8 aspect-square">
                </div>
                <div class="space-y-1 font-sans text-lg tracking-wide text-center">
                    <div class="text-xs uppercase text-slate-400">
                        {{ usePage().props.auth.studio.category }} studio
                    </div>
                    <div class="text-white font-lemon">
                        {{ usePage().props.auth.studio.name }}
                    </div>
                </div>

                <div class="space-y-2 text-xs text-center">
                    <template v-if="usePage().props.auth.studio.is_published">
                        <div class="flex items-center justify-center gap-1 text-green-500">
                            <i class="text-sm fa-solid fa-check" />
                            Studio pubblicato
                        </div>
                        <Link :href="route('studio.show', usePage().props.auth.studio.slug)" class="flex items-center justify-center gap-1 text-orange-500 transition-colors hover:text-orange-400">
                            Vai alla pagina Studio
                            <i class="fa-solid fa-up-right-from-square" />
                        </Link>
                    </template>
                    <template v-else-if="!usePage().props.auth.studio.is_complete">
                        <div class="flex items-center justify-center gap-1 text-amber-500">
                            <i class="text-sm fa-solid fa-circle-exclamation" />
                            Studio non pubblicato
                        </div>
                        <div class="flex items-center justify-center gap-1 text-red-500">
                            <i class="text-sm fa-solid fa-circle-exclamation" />
                            Dati minimi non completati
                        </div>
                    </template>
                    <template v-else-if="!usePage().props.auth.studio.is_published && usePage().props.auth.studio.is_complete">
                        <Link :href="route('studio.description.edit')" class="flex items-center justify-center gap-1 transition-colors hover:text-orange-500 text-amber-500">
                            <i class="text-sm fa-solid fa-circle-exclamation" />
                            Studio non pubblicato
                        </Link>
                        <div class="flex items-center justify-center gap-1 text-green-500">
                            <i class="text-sm fa-solid fa-check" />
                            Dati minimi completati
                        </div>
                    </template>
                </div>
            </div>
    
            <!-- menu -->
            <div class="space-y-2 list-none list-image-none">
                <template v-for="item, index in menu" :key="index">
                    <div v-if="item.route && !item.children.length">
                        <Link :href="route(item.route)" class="flex items-center gap-2 px-4 py-3 text-sm transition-colors md:py-2 hover:text-orange-400" :class="route().current(item.active) ? 'text-white' : 'text-slate-400'">
                            <i class="w-5 text-center" :class="item.icon" />
                            {{ item.label }}
                        </Link>
                    </div>
    
                    <div v-else>
                        <details open @toggle="toggleDetailState(index)">
                            <summary class="flex items-center justify-between px-4 py-3 text-sm transition-colors cursor-pointer md:py-2 hover:text-orange-400" :class="route().current(item.active) ? 'text-slate-200' : 'text-slate-400'">
                                <div class="flex items-center gap-2">
                                    <i class="w-5 text-center" :class="item.icon" />
                                    {{ item.label }}
                                </div>
    
                                <i class="text-orange-500 transition-transform fa-solid fa-chevron-down" :class="{'rotate-180' : detailState[index]}" />
                            </summary>
                            
                            <div class="mt-1 ml-6 space-y-1 list-none border-l list-image-none border-slate-600">
                                <div v-for="child in item.children">
                                    <Link :href="route(child.route)" class="flex items-center gap-2 px-4 py-2 md:py-1.5 text-xs transition-colors font-normal hover:text-orange-400" :class="route().current(child.active) ? 'text-white' : 'text-slate-400'">
                                        <i class="w-5 text-center" :class="child.icon" />
                                        {{ child.label }}
                                    </Link>
                                </div>
                            </div>
                        </details>
                    </div>
                </template>
            </div>
            <!-- / -->
        </div>
    </nav>
</template>

<script setup>
import { computed, ref } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import menuItems from './menuItems.json';

const detailState = ref({});

const toggleDetailState = (index)=>{
    detailState.value[index] = !detailState.value[index];
};

const menu = computed(()=>{
    let menu = [];
    let roleId = usePage().props.auth.user.role_id;

    if(roleId === 2){
        //menu studio
        menu = menuItems.studio
    } else if(roleId === 3){
        //menu artista
        menu = []
    }

    return menu;
});

</script>
