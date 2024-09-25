<template>
    <component :is="props.as" @submit.prevent="props.as === 'form' ? emit('submitted') : null" class="h-full lg:bg-slate-900">
        <fieldset :disabled="props.isLoading" class="flex flex-col h-full">
            <h1 v-if="props.title" class="z-10 flex items-center justify-between h-12 gap-4 px-4 m-0 text-base border-b-2 shadow-md shrink-0 lg:bg-slate-800 bg-slate-900 md:text-xl lg:h-16 lg:px-6 border-slate-800 lg:border-0">
                <div class="flex items-center w-full gap-2 leading-tight md:gap-3">
                    <Link v-if="props.backRoute" :href="props.backRoute" class="lg:hidden">
                        <i class="mr-2 text-lg text-orange-500 fa-solid fa-chevron-left"></i>
                    </Link>

                    <i v-if="props.icon" :class="props.icon"></i>

                    {{ props.title }}
                </div>
                
                <div v-if="$slots.actions" class="fixed lg:static bottom-20 right-4">
                    <slot name="actions"/>
                </div>

                <UserMenu class="lg:hidden shrink-0" />
            </h1>
    
            <div v-if="$slots.content" class="p-4 space-y-8 overflow-y-auto grow lg:p-6" :class="{'pb-20' : $slots.actions}">
                <Tabs v-if="props.tabLinks" :tabLinks="tabLinks"/>

                <p v-if="$slots.description" class="pb-6 text-sm border-b text-slate-300 border-slate-800">
                    <slot name="description"/>
                </p>

                <slot name="content"/>
            </div>
        </fieldset>
    </component>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserMenu from '@/Components/Backoffice/UserMenu.vue';
import Tabs from '@/Components/Backoffice/Tabs.vue';

const props = defineProps({
    title: String,
    icon: String,
    isLoading: Boolean,
    onSuccess: Boolean,
    onFail: Boolean,
    flashMessage: {
        type: String,
        default: 'Salvato!'
    },
    as: {
        type: String,
        default: 'form'
    },
    tabLinks: Object,
});

const emit = defineEmits(['submitted']);

</script>
