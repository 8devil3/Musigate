<template>
    <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="flex flex-col h-full overflow-hidden">
        <h1 v-if="props.title" class="flex items-center justify-between w-full gap-2 px-4 m-0 text-base border-b h-14 lg:h-16 lg:px-6 bg-slate-900 md:text-xl border-b-slate-800 shrink-0">
            <div class="flex items-center gap-2 overflow-hidden leading-tight grow md:gap-3">
                <Link v-if="props.backRoute" :href="route(props.backRoute, props.backRouteParams)" class="shrink-0">
                    <i class="mr-1 text-base text-orange-500 lg:text-lg fa-solid fa-chevron-left" />
                </Link>

                <i v-if="props.icon" :class="props.icon" class="shrink-0" />

                <div class="truncate">
                    {{ props.title }}
                </div>
            </div>

            <UserMenu />
        </h1>

        <fieldset :disabled="isLoading" class="flex flex-col overflow-hidden grow">
            <div class="flex flex-col overflow-y-auto grow">
                <div v-if="$slots.content" class="w-full max-w-6xl p-4 pb-20 mx-auto space-y-8 grow lg:p-8">
                    <Tabs v-if="props.tabLinks" :tabLinks="tabLinks"/>
    
                    <p v-if="$slots.description" class="pb-6 text-sm border-b text-slate-300 border-slate-800">
                        <slot name="description"/>
                    </p>
    
                    <slot name="content"/>
                </div>
            </div>

            <!-- actions -->
            <div class="border-t shrink-0 bg-slate-900/30 border-t-slate-800">
                <div v-if="$slots.actions" class="flex max-w-6xl mx-auto w-full *:grow md:*:grow-0 gap-2 p-4 lg:px-8 md:justify-end">
                    <slot name="actions"/>
                </div>
            </div>
            <!-- / -->
        </fieldset>

        <AppBar class="lg:hidden" />
    </component>

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
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import AppBar from '@/Components/Backoffice/AppBar.vue';

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

