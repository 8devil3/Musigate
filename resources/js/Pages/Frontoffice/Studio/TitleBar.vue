<template>
    <div class="space-y-6">
        <!-- link per tonare alla ricerca -->
        <div class="pb-4">
            <BackLink text="Torna alla ricerca" :router="route('studio.index', props.request)" />
        </div>
        <!-- / -->

        <div class="flex items-center justify-between gap-8">
            <div class="flex items-center gap-4">
                <!-- studio logo -->
                <div class="flex items-center justify-center w-16 h-16 text-center border-2 border-orange-500 rounded-full bg-slate-900 md:w-24 md:h-24 shrink-0">
                    <img v-if="props.studio.logo" :src="'/storage/' + props.studio.logo" :alt="props.studio.name" class="object-contain w-full h-full rounded-full">
                    <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.studio.name" class="object-contain w-1/2 aspect-square">
                </div>
                <!-- / -->
                
                <!-- categoria & nome -->
                <div class="space-y-2">
                    <div v-if="props.studio.category" class="text-sm leading-none uppercase font-lemon text-slate-400">
                        {{ props.studio.category }} studio
                    </div>

                    <h1 class="m-0 text-2xl lg:text-4xl">{{ props.studio.name }}</h1>
                </div>
                <!-- / -->
            </div>

            <!-- titolare studio: desktop -->
            <div class="hidden gap-4 lg:flex">
                <Avatar :first_name="props.user.first_name" :last_name="props.user.last_name" :img="props.user.avatar" class="text-lg w-14 h-14" />
                <div class="space-y-1.5">
                    <div class="text-[14px] tracking-wide font-lemon pr-2">
                        Titolare Studio
                    </div>
                    <div class="h-[1px] bg-orange-500"></div>
                    <div>
                        {{ props.user.first_name }} {{ props.user.last_name }}
                    </div>
                </div>
            </div>
            <!-- / -->
        </div>
    
        <div class="flex flex-col items-start sm:flex-row sm:flex-wrap gap-y-6 gap-x-12">
            <!-- indirizzo -->
            <div class="flex items-start gap-2">
                <i class="text-base text-orange-500 fa-solid fa-location-dot"></i>
                <address class="text-sm capitalize md:text-base">
                    {{ props.studio.location.address }}, {{ props.studio.location.number }}<br>
                    {{ props.studio.location.city }} - <span class="normal-case">{{ props.studio.location.province }}</span>
                </address>
            </div>
            <!-- indirizzo -->

            <!-- etichetta discografica -->
            <div v-if="props.studio.record_label" class="flex items-center gap-1 text-sm md:text-base">
                <svg class="w-6 h-6 -ml-1" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="9.5" fill="#000000" stroke="#FF6600"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16ZM12 12.8C12.4418 12.8 12.8 12.4418 12.8 12C12.8 11.5582 12.4418 11.2 12 11.2C11.5582 11.2 11.2 11.5582 11.2 12C11.2 12.4418 11.5582 12.8 12 12.8Z" fill="#FF6600"/>
                </svg>

                <span>
                    Etichetta discografica
                </span>
            </div>
            <!-- / -->

            <!-- social -->
            <div v-if="props.studio.social" class="flex items-center gap-3 lg:ml-auto">
                <a v-if="props.studio.social['facebook']" :href="props.studio.social['facebook']" title="Facebook" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-facebook"></i>
                </a>

                <a v-if="props.studio.social['instagram']" :href="props.studio.social['instagram']" title="Instagram" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-instagram"></i>
                </a>

                <a v-if="props.studio.social['linkedin']" :href="props.studio.social['linkedin']" title="LinkedIn" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-linkedin"></i>
                </a>

                <a v-if="props.studio.social['youtube']" :href="props.studio.social['youtube']" title="YouTube" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-youtube"></i>
                </a>

                <a v-if="props.studio.social['soundcloud']" :href="props.studio.social['soundcloud']" title="SoundCloud" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-soundcloud"></i>
                </a>

                <a v-if="props.studio.social['spotify']" :href="props.studio.social['spotify']" title="Spotify" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-spotify"></i>
                </a>

                <a v-if="props.studio.social['itunes']" :href="props.studio.social['itunes']" title="iTunes" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-brands fa-itunes"></i>
                </a>

                <a v-if="props.studio.social['website']" :href="props.studio.social['website']" title="Website" class="text-center transition-colors hover:text-orange-500">
                    <i class="text-2xl fa-solid fa-globe"></i>
                </a>
            </div>
            <!-- / -->
        </div>

        <!-- titolare studio: mobile -->
        <div class="flex gap-4 lg:hidden">
            <Avatar :first_name="props.user.first_name" :last_name="props.user.last_name" :img="props.user.avatar" class="text-lg w-14 h-14" />
            <div class="space-y-1.5">
                <div class="text-[14px] tracking-wide font-lemon pr-2">
                    Titolare Studio
                </div>
                <div class="h-[1px] bg-orange-500"></div>
                <div>
                    {{ props.user.first_name }} {{ props.user.last_name }}
                </div>
            </div>
        </div>
        <!-- / -->
    </div>
</template>

<script setup>
import Avatar from '@/Components/Avatar.vue';
import BackLink from '@/Components/BackLink.vue';

const props = defineProps({
    studio: Object,
    user: Object,
    request: Object
});

</script>
