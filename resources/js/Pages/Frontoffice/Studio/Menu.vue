<template>
    <nav class="sticky top-0 z-40 border-slate-700 border-y bg-slate-950/40 backdrop-blur-sm">
        <ul class="flex items-center w-full max-w-6xl gap-8 px-4 mx-auto overflow-x-auto md:px-6">
            <template v-for="link in props.links">
                <li v-if="link.enabled">
                    <a :href="link.id" class="block py-3 text-xs tracking-wide uppercase transition-colors border-b-2 border-transparent font-lemon lg:text-sm lg:py-4 whitespace-nowrap hover:border-orange-500 active:border-orange-500">
                        {{ link.text }}
                    </a>
                </li>
            </template>
        </ul>
    </nav>
</template>

<script setup>
const props = defineProps({
    links: Object
});

</script>

