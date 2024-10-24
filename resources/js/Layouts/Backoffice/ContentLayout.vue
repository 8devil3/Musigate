<template>
    <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="min-h-full lg:bg-slate-900/50">
        <fieldset :disabled="props.isLoading">
            <h1 v-if="props.title" class="sticky top-0 z-10 flex items-center justify-between w-full gap-2 px-4 m-0 text-base border-b h-14 lg:h-16 lg:px-6 bg-slate-900 lg:bg-slate-900/50 backdrop-blur-sm md:text-xl border-b-slate-800">
                <div class="flex items-center gap-2 overflow-hidden leading-tight grow md:gap-3">
                    <Link v-if="props.backRoute" :href="route(props.backRoute, props.backRouteParams)" class="shrink-0">
                        <i class="mr-1 text-base text-orange-500 lg:text-lg fa-solid fa-chevron-left" />
                    </Link>

                    <i v-if="props.icon" :class="props.icon" class="shrink-0" />

                    <div class="truncate">
                        {{ props.title }}
                    </div>
                </div>

                <button type="button" @click="isOpenDrawer = true" class="shrink-0 lg:hidden">
                    <i class="py-1 pr-1 text-lg fa-solid fa-bars" />
                </button>
            </h1>
    
            <div v-if="$slots.content" class="p-4 pb-20 space-y-8 lg:p-6">
                <Tabs v-if="props.tabLinks" :tabLinks="tabLinks"/>

                <p v-if="$slots.description" class="pb-6 text-sm border-b text-slate-300 border-slate-800">
                    <slot name="description"/>
                </p>

                <slot name="content"/>
            </div>

            <!-- actions -->
            <div v-if="$slots.actions" class="sticky bottom-16 lg:bottom-0 flex *:grow md:*:grow-0 gap-2 p-4 border-t md:justify-end bg-slate-900/70 lg:bg-slate-950/50 backdrop-blur-sm border-t-slate-800">
                <slot name="actions"/>
            </div>
            <!-- / -->
        </fieldset>

    </component>


    <!-- drawer mobile -->
    <Drawer :isOpen="isOpenDrawer" @close="isOpenDrawer = false">
        <AsideMenu />
    </Drawer>
    <!-- / -->


    <!-- spinner -->
    <teleport to="#spinner">
        <div v-if="isLoading" class="fixed inset-0 z-[5000] flex items-center justify-center backdrop-blur-sm bg-black/30">
            <Spinner class="size-16 orange"/>
        </div>
    </teleport>
    <!-- / -->
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Tabs from '@/Components/Backoffice/Tabs.vue';
import Spinner from '@/Components/Spinner.vue';
import Drawer from '@/Components/Drawer.vue';
import AsideMenu from '@/Components/Backoffice/AsideMenu.vue';

const props = defineProps({
    title: String,
    icon: String,
    isSucess: Boolean,
    hasErrors: Boolean,
    backRoute: String,
    backRouteParams: [Array, Number, Object],
    tabLinks: Object,
    as: {
        type: String,
        default: 'form'
    },
});

const emit = defineEmits(['submitted']);
const isLoading = ref(false);
const isOpenDrawer = ref(false);


router.on('start', ()=> isLoading.value = true);
router.on('finish', ()=> isLoading.value = false);

</script>

