<template>
    <section :id="props.id">
        <h2 class="pb-1.5 mb-6 border-b-2 border-orange-500">
            {{ props.title }}
        </h2>
        <div class="space-y-4">
            <slot />
        </div>
    </section>
</template>

<script setup>
const props = defineProps({
    title: String,
    id: String
});
</script>
