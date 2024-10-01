<template>
    <div class="w-full max-w-md py-8 mx-auto space-y-2 text-center lg:mt-0">
        <i :class="'text-2xl text-slate-500 ' + props.icon"></i>
        <h4 class="text-base font-normal text-slate-500">
            <slot name="title"/>
        </h4>
        <p class="text-sm font-normal text-slate-500">
            <slot name="description"/>
        </p>
        <div class="pt-4">
            <slot name="actions"/>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    icon: String,
});

</script>
