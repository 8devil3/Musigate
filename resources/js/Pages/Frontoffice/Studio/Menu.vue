<template>
    <nav class="sticky top-0 z-40 border-b border-gray-700 bg-opacity-40 bg-gray-950 backdrop-blur-sm">
        <ul class="flex items-center w-full max-w-6xl px-4 mx-auto overflow-x-auto gap-11">
            <li v-for="link in props.links">
                <a :href="link.id" v-if="link.enabled" class="block py-3 text-xs tracking-wide uppercase transition-colors border-b-2 border-transparent font-lemon lg:text-sm lg:py-4 whitespace-nowrap hover:border-orange-500 active:border-orange-500">
                    {{ link.text }}
                </a>
            </li>
        </ul>
    </nav>
</template>

<script setup>
const props = defineProps({
    links: Object
});

</script>

