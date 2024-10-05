<template>
    <div>
        <a :href="route('google.redirect')" class="flex items-center justify-between w-full h-8 gap-3 px-2 hover:bg-slate-800 transition-colors text-center py-1.5 bg-slate-800/50 border border-slate-400 rounded-full text-sm font-medium">
            <img src="/img/logo/icon_google.svg" class="h-full" />
            Continua con Google
            <div class="w-[18px]" />
        </a>
    </div>

    <div class="flex items-center gap-2 text-sm font-title text-slate-100">
        <hr class="w-full h-0 border-t border-slate-400" />
        oppure
        <hr class="w-full h-0 border-t border-slate-400" />
    </div>
</template>

<script setup>

</script>
