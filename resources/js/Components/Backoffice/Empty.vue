<template>
    <div class="w-full max-w-md py-8 mx-auto space-y-2 text-center lg:mt-0">
        <i :class="'text-3xl text-gray-400 ' + props.icon"></i>
        <h4 class="text-lg font-normal text-gray-400/90">
            <slot name="title"/>
        </h4>
        <p class="text-sm text-gray-400">
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
