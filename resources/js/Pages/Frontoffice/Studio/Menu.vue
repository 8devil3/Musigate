<template>
    <nav class="sticky top-0 z-40 border-b bg-slate-950/50 backdrop-blur-sm border-b-slate-800">
        <ul class="flex items-center w-full max-w-6xl gap-6 px-4 py-0 mx-auto overflow-x-auto md:px-6">
            <template v-for="link in props.links">
                <li v-if="link.enabled" class="p-0">
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

