<template>
    <div class="space-y-6">
        <!-- link per tonare alla ricerca -->
        <div class="pb-4">
            <BackLink label="Torna alla ricerca" :href="route('studio.index', props.request)" />
        </div>
        <!-- / -->

        <!-- logo e categoria -->
        <div class="flex items-center gap-4">
            <!-- logo -->
            <div class="flex items-center justify-center text-center border-2 border-orange-500 rounded-full size-16 bg-slate-900 lg:w-24 lg:h-24 shrink-0">
                <img v-if="props.studio.logo" :src="'/storage/' + props.studio.logo" :alt="props.studio.name" class="object-contain w-full h-full rounded-full">
                <img v-else src="/img/logo/logo_placeholder.svg" :alt="props.studio.name" class="object-contain w-1/2 aspect-square">
            </div>
            <!-- / -->
            
            <!-- categoria & nome -->
            <div class="space-y-2">
                <div v-if="props.studio.category" class="text-sm font-normal leading-none uppercase text-slate-400">
                    {{ props.studio.category }} studio
                </div>

                <h1 class="m-0 text-2xl lg:text-4xl">{{ props.studio.name }}</h1>

                <p v-if="props.studio.category === 'Professional'" class="text-xs font-normal uppercase text-slate-400">p.iva {{ props.studio.vat }}</p>
            </div>
            <!-- / -->
        </div>
        <!-- / -->

        <address class="flex flex-wrap gap-y-8 gap-x-4">
            <div class="flex flex-wrap items-start gap-8 sm:flex-nowrap">
                <!-- indirizzo -->
                <div class="flex items-start gap-2">
                    <i class="text-base text-orange-500 fa-solid fa-location-dot"></i>
                    <div class="text-sm capitalize md:text-base">
                        {{ props.studio.location.address }}, {{ props.studio.location.number }}
                        <div>{{ props.studio.location.city }}</div>
                        <div class="normal-case">{{ props.studio.location.province }}</div>
                    </div>
                </div>
                <!-- / -->

                <!-- etichetta discografica -->
                <div v-if="props.studio.is_record_label" class="flex items-start gap-1 text-sm md:text-base">
                    <svg class="-ml-1 size-6" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9.5" fill="#000000" stroke="#FF6600"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16ZM12 12.8C12.4418 12.8 12.8 12.4418 12.8 12C12.8 11.5582 12.4418 11.2 12 11.2C11.5582 11.2 11.2 11.5582 11.2 12C11.2 12.4418 11.5582 12.8 12 12.8Z" fill="#FF6600"/>
                    </svg>
        
                    <span>
                        Etichetta discografica
                    </span>
                </div>
                <!-- / -->
            </div>

            <!-- social -->
            <ul v-if="props.socials && computedSocials.length" class="flex flex-wrap gap-1 p-0 m-0 list-none place-items-center sm:grid sm:grid-cols-4 sm:ml-auto list-image-none">
                <li v-for="social in computedSocials" class="p-0">
                    <a :href="social.href" :title="social.label" class="flex items-center justify-center w-8 text-center text-white">
                        <i :class="'text-2xl transition-colors hover:text-orange-500 ' + social.icon" />
                    </a>
                </li>
            </ul>
            <!-- / -->
        </address>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Avatar from '@/Components/Avatar.vue';
import BackLink from '@/Components/BackLink.vue';

const props = defineProps({
    studio: Object,
    socials: Object,
    request: Object
});

const computedSocials = computed(()=>{
    let arrSocials = Object.entries(props.socials);
    let socials = [];

    if(arrSocials.length){
        arrSocials.forEach(social => {
            let label = social[0];
            let href = social[1];
            let icon = null;
            
            if(href){
                switch (label) {
                    case 'linkedin':
                        icon = 'fa-brands fa-linkedin text-linkedin';
                    break;
                    case 'soundcloud':
                        icon = 'fa-brands fa-soundcloud text-soundcloud';
                    break;
                    case 'facebook':
                        icon = 'fa-brands fa-facebook text-facebook';
                    break;
                    case 'itunes':
                        icon = 'fa-brands fa-itunes text-itunes';
                    break;
                    case 'spotify':
                        icon = 'fa-brands fa-spotify text-spotify';
                    break;
                    case 'youtube':
                        icon = 'fa-brands fa-youtube text-youtube';
                    break;
                    case 'instagram':
                        icon = 'fa-brands fa-instagram text-instagram';
                    break;
                    case 'website':
                        icon = 'fa-solid fa-globe text-white';
                    break;
                }

                socials.push({
                    label: label,
                    href: href,
                    icon: icon,
                });
            }
        });
    }

    return socials;
});

</script>
