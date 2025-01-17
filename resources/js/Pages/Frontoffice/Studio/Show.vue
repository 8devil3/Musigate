<template>
    <FrontofficeLayout :title="props.studio.name">
        <!-- galleria -->
        <Gallery :photos="props.all_photos" />
        <!-- / -->

        <!-- menu orizzontale -->
        <Menu :links="links" />
        <!-- / -->

        <!-- contenuto -->
        <div class="w-full max-w-6xl px-4 py-6 mx-auto md:px-6 md:py-8">
            <!-- sezioni -->
            <div class="space-y-16">
                <TitleBar :studio="props.studio" :socials=props.socials :request="props.request" />

                <Section v-if="props.studio.description" title="Presentazione" id="presentazione">
                    <p class="leading-relaxed whitespace-pre-wrap">{{ props.studio.description }}</p>
            
                    <div v-if="props.studio.payment_methods.length" class="pt-2 space-y-2">
                        <h3>Pagamenti accettati</h3>
            
                        <ul class="flex flex-wrap gap-2 p-0">
                            <li v-for="payment in props.studio.payment_methods" class="p-0">
                                <img :src="'/img/payments/' + payment.img_name" :alt="payment.name" class="h-6 w-9 md:h-8 md:w-11" :title="payment.name">
                            </li>
                        </ul>
                    </div>
                </Section>

                <Section v-if="props.studio.collaborations.length" title="Collaborazioni" id="collaborazioni">
                    <div class="pb-4 mb-6 overflow-x-auto">
                        <ul class="flex p-0 list-none list-image-none">
                            <div class="grid grid-cols-1 grid-rows-2 shrink-0">
                                <div class="w-6 border-b-2 border-b-slate-500" />
                            </div>
                            <div v-for="collab, index in props.studio.collaborations" class="grid grid-cols-1 grid-rows-2 max-w-64 shrink-0">
                                <template v-if="index %2 === 0">
                                    <li class="relative pb-6 pl-2 pr-12 leading-normal border-b-2 border-l border-l-slate-500 border-b-slate-500">
                                        <div class="text-xs font-normal uppercase shrink-0 text-slate-400">
                                            {{ props.months[collab.month] }}
                                            {{ collab.year }}
                                        </div>

                                        <h3 class="w-full p-0 m-0 text-sm leading-normal line-clamp-2">{{ collab.title }}</h3>

                                        <ul v-if="collab.spotify || collab.soundcloud || collab.itunes" class="flex gap-2 p-0">
                                            <li v-if="collab.spotify"><a :href="collab.spotify" class="p-0 transition-colors hover:text-orange-500 text-spotify">
                                                <i class="text-lg fa-brands fa-spotify" />
                                            </a></li>
                                            <li v-if="collab.soundcloud"><a :href="collab.soundcloud" class="p-0 transition-colors hover:text-orange-500 text-soundcloud">
                                                <i class="text-lg fa-brands fa-soundcloud" />
                                            </a></li>
                                            <li v-if="collab.itunes"><a :href="collab.itunes" class="p-0 transition-colors hover:text-orange-500 text-itunes">
                                                <i class="text-lg fa-brands fa-itunes" />
                                            </a></li>
                                        </ul>

                                        <div class="absolute border-2 border-orange-500 rounded-full -bottom-2 size-4 bg-slate-800 -left-2" />
                                    </li>
                                    <div />
                                </template>
                                <template v-else>
                                    <div />
                                    <li class="relative flex flex-col justify-end pt-6 pl-2 pb-0 -mt-0.5 pr-12 border-t-2 border-l border-l-slate-500 border-t-slate-500 leading-normal">
                                        <div class="text-xs font-normal uppercase shrink-0 text-slate-400">
                                            {{ props.months[collab.month] }}
                                            {{ collab.year }}
                                        </div>

                                        <h3 class="w-full p-0 m-0 text-sm leading-normal line-clamp-2">{{ collab.title }}</h3>

                                        <ul v-if="collab.spotify || collab.soundcloud || collab.itunes" class="flex gap-2 p-0">
                                            <li v-if="collab.spotify"><a :href="collab.spotify" class="p-0 transition-colors hover:text-orange-500 text-spotify">
                                                <i class="text-lg fa-brands fa-spotify" />
                                            </a></li>
                                            <li v-if="collab.soundcloud"><a :href="collab.soundcloud" class="p-0 transition-colors hover:text-orange-500 text-soundcloud">
                                                <i class="text-lg fa-brands fa-soundcloud" />
                                            </a></li>
                                            <li v-if="collab.itunes"><a :href="collab.itunes" class="p-0 transition-colors hover:text-orange-500 text-itunes">
                                                <i class="text-lg fa-brands fa-itunes" />
                                            </a></li>
                                        </ul>

                                        <div class="absolute border-2 border-orange-500 rounded-full -top-2 size-4 bg-slate-800 -left-2" />
                                    </li>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 grid-rows-2 shrink-0 grow">
                                <div class="w-full border-b-2 border-b-slate-500" />
                            </div>
                        </ul>
                    </div>

                    <ShowAll @click="openCollabModal = true" />

                    <Modal :isOpen="openCollabModal" @close="openCollabModal = false">
                        <template #title>
                            Collaborazioni
                        </template>

                        <template #description>
                            <ul class="relative ml-1.5 p-0 border-l-2 border-orange-500">                  
                                <li v-for="collab in props.studio.collaborations" class="p-0 mb-12 ml-4 last-of-type:mb-0">
                                    <div class="absolute w-3.5 h-3.5 bg-zinc-900 rounded-full mt-2 -left-2 border-2 border-orange-500" />

                                    <div class="pt-1.5 space-y-2">
                                        <div>
                                            <div class="text-xs font-normal uppercase text-slate-400">{{ props.months[collab.month] }} {{ collab.year }}</div>
                                            <h3 class="leading-normal">{{ collab.title }}</h3>
                                        </div>

                                        <p v-if="collab.description" class="pb-0 text-sm text-white">{{ collab.description }}</p>

                                        <ul v-if="collab.spotify || collab.soundcloud || collab.itunes" class="flex gap-2 p-0">
                                            <li v-if="collab.spotify"><a :href="collab.spotify" class="p-0 transition-colors hover:text-orange-500 text-spotify">
                                                <i class="text-xl fa-brands fa-spotify" />
                                            </a></li>
                                            <li v-if="collab.soundcloud"><a :href="collab.soundcloud" class="p-0 transition-colors hover:text-orange-500 text-soundcloud">
                                                <i class="text-xl fa-brands fa-soundcloud" />
                                            </a></li>
                                            <li v-if="collab.itunes"><a :href="collab.itunes" class="p-0 transition-colors hover:text-orange-500 text-itunes">
                                                <i class="text-xl fa-brands fa-itunes" />
                                            </a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </template>
                    </Modal>
                </Section>

                <Section v-if="props.studio.comforts.length" title="Comfort" id="comfort">
                    <ul class="gap-8 sm:columns-2 md:columns-3 lg:columns-4 xl:columns-5 list-musigate">
                        <li v-for="comfort, index in props.studio.comforts" :title="comfort.name">
                            <div>{{ comfort.name }}</div>
                        </li>
                    </ul>
                </Section>

                <Section v-if="props.studio.services.length" title="Servizi" id="servizi">
                    <ul class="gap-8 sm:columns-2 md:columns-3 lg:columns-4 xl:columns-5 list-musigate">
                        <li v-for="service, index in props.studio.services" :title="service.name">
                            <div>{{ service.name }}</div>
                        </li>
                    </ul>
                </Section>

                <Section v-if="props.studio.rooms.length" title="Sale" id="sale">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <RoomCard v-for="room in props.studio.rooms" :room="room" :equipment_categories="props.equipment_categories" :weekdays="props.weekdays" />
                    </div>
                </Section>

                <Section v-if="props.studio.bundles.length" title="Pacchetti" id="pacchetti">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <BundleCard v-for="bundle in props.studio.bundles" :bundle="bundle" :weekdays="weekdays" />
                    </div>
                </Section>

                <Section v-if="props.studio.videos.length" title="Video" id="video">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <iframe v-for="video in props.studio.videos" :src="'https://www.youtube.com/embed/' + video.yt_id" frameborder="0" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; web-share" allowfullscreen class="w-full border border-slate-700 rounded-xl aspect-video"></iframe>
                    </div>                    
                </Section>

                <Section v-if="props.studio.rule.before || props.studio.rule.during || props.studio.rule.after" title="Regolamento" id="regolamento">
                    <div v-if="props.studio.rule.before" class="mb-6">
                        <h3 class="mb-1 text-sm">Prima della sessione</h3>
                        <p class="p-0 whitespace-pre-wrap">{{ props.studio.rule.before.substring(0, 300) }}...</p>
                    </div>
            
                    <div v-if="props.studio.rule.during" class="mb-6">
                        <h3 class="mb-1 text-sm">Durante la sessione</h3>
                        <p class="p-0 whitespace-pre-wrap">{{ props.studio.rule.during.substring(0, 300) }}...</p>
                    </div>
            
                    <div v-if="props.studio.rule.after">
                        <h3 class="mb-1 text-sm">Dopo la sessione</h3>
                        <p class="p-0 whitespace-pre-wrap">{{ props.studio.rule.after.substring(0, 300) }}...</p>
                    </div>
                    
                    <ShowAll @click="openRulesModal = true" />

                    <Modal :isOpen="openRulesModal" @close="openRulesModal = false">
                        <template #title>
                            Regolamento
                        </template>

                        <template #description>
                            <div class="space-y-8">
                                <div v-if="props.studio.rule.before">
                                    <h4>Prima della sessione</h4>
                                    <p>{{ props.studio.rule.before }}</p>
                                </div>
                        
                                <div v-if="props.studio.rule.during">
                                    <h4>Durante la sessione</h4>
                                    <p>{{ props.studio.rule.during }}</p>
                                </div>
                        
                                <div v-if="props.studio.rule.after">
                                    <h4>Dopo la sessione</h4>
                                    <p>{{ props.studio.rule.after }}</p>
                                </div>
                            </div>
                        </template>
                    </Modal>
                </Section>

                <Section v-if="props.studio.cancel_settings.has_cancel_policy" title="Annullamenti" id="annullamenti">
                    <div>
                        <p>
                            Nessun rimborso fino a <span class="font-semibold text-orange-500">{{ props.studio.cancel_settings.no_refund_hours }} ore </span> prima della data/ora di prenotazione.
                        </p>
                        <p>
                            Da <span class="font-semibold text-orange-500">{{ props.studio.cancel_settings.no_refund_hours }} ore</span> e fino a <span class="font-semibold text-orange-500">{{ props.studio.cancel_settings.partial_refund_hours }} ore</span> prima della data/ora di prenotazione, l'artista riceverà un rimborso del <span class="font-semibold text-orange-500">{{ props.studio.cancel_settings.partial_refund_percentage }}%</span>.
                        </p>
                        <p>
                            Oltre <span class="font-semibold text-orange-500">{{ props.studio.cancel_settings.partial_refund_hours }} ore</span> riceverà il rimborso totale (100%).
                        </p>
                    </div>
                </Section>

                <Section title="Contatti" id="contatti">
                    <Button v-if="!props.contacts" @click="router.reload({ only: ['contacts'] })" text="Mostra contatti" :isLoading="isLoading" :disabled="isLoading" />
                    
                    <p v-else-if="!computedContacts.length" class="text-sm text-slate-300">Lo Studio non ha inserito alcun contatto.</p>

                    <ul v-else class="grid grid-cols-1 p-0 m-0 list-none gap-x-8 gap-y-4 sm:grid-cols-3 md:flex md:flex-wrap list-image-none">
                        <li v-for="contact in computedContacts">
                            <a :href="contact.href" class="text-base font-normal transition-colors hover:text-orange-500" :class="contact.color">
                                <i class="w-5 mr-1 text-lg text-center" :class="contact.icon" />
                                {{ contact.label }}
                            </a>
                        </li>
                    </ul>
                </Section>

                <Section title="Orari" id="orari">
                    <ul class="grid grid-cols-2 p-0 list-none list-image-none gap-x-2 gap-y-8 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8">
                        <li v-for="av in props.studio.availability" class="p-0 space-y-2">
                            <div>
                                <div class="font-normal capitalize" :class="av.open_type !== 'close' ? 'text-white' : 'text-slate-400'">
                                    {{ props.weekdays[av.weekday] }}
                                </div>
                                <div v-if="av.open_type === 'open'" class="text-xs font-normal text-green-500">
                                    aperto
                                </div>
                                <div v-else-if="av.open_type === 'open_h24'" class="text-xs font-normal text-sky-500">
                                    aperto h24
                                </div>
                                <div v-else-if="av.open_type === 'open_overnight'" class="text-xs font-normal text-blue-500">
                                    aperto overnight
                                </div>
                                <div v-else-if="av.open_type === 'open_forewarning'" class="text-xs font-normal text-amber-500">
                                    aperto su richiesta con preavviso di {{ av.min_forewarning === 1 ? av.min_forewarning + ' giorno' : av.min_forewarning + ' giorni' }}
                                </div>
                                <div v-else-if="av.open_type === 'close'" class="text-xs font-normal text-red-500">
                                    chiuso
                                </div>
                            </div>

                            <div class="text-xs">
                                <template v-if="av.open_type !== 'close' && av.open_type !== 'open_forewarning'">
                                    <time :datetime="av.open_start">
                                        {{ av.open_start }}
                                    </time>
                                    -
                                    <time :datetime="av.open_end">
                                        {{ av.open_end }}
                                    </time>
                                </template>
                                <template v-else-if="av.open_type === 'close'">
                                    -
                                </template>
                            </div>

                            <div v-if="av.weekday === 8">
                                <button type="button" @click="openHolydaysListModal = true" class="text-xs font-normal text-left text-orange-500 transition-colors hover:text-orange-400">
                                    <i class="fa-solid fa-list" />
                                    Giorni festivi
                                </button>
                            </div>
                        </li>
                    </ul>

                    <Modal :isOpen="openHolydaysListModal" @close="openHolydaysListModal = false">
                        <template #title>
                            Definizione di giorni festivi
                        </template>
                        <template #description>
                            <div class="space-y-2">
                                <p>Musigate considera giorni festivi quelli stabiliti per legge ad esclusione delle domeniche che sono gestite separatamente:</p>

                                <ul class="list-disc">
                                    <li v-for="holyday in props.holydays" class="pb-0.5">{{ holyday }}</li>
                                </ul>
                            </div>
                        </template>
                    </Modal>
                </Section>

                <Section title="Location" id="location">
                    <div class="space-y-6">
                        <div class="flex flex-wrap items-start sm:flex-nowrap gap-x-10 gap-y-4">
                            <div class="sm:shrink-0">
                                <h3 class="mb-1 text-sm">Indirizzo</h3>
                                <address class="text-sm capitalize">
                                    <div>
                                        {{ props.studio.location.address }}
                                        {{ props.studio.location.number ? ', ' + props.studio.location.number : '' }}
                                    </div>
                                    <div>
                                        {{ props.studio.location.cap }} {{ props.studio.location.city }}
                                    </div>
                                    <div>
                                        {{ props.studio.location.province }}
                                    </div>
                                </address>
                            </div>
                            <div v-if="props.studio.location.notes">
                                <h3 class="mb-1 text-sm">Come arrivare</h3>
                                <p>{{ props.studio.location.notes }}</p>
                            </div>
                        </div>

                        <GoogleMaps
                            :studios="[props.studio]"
                            :lat="props.studio.location.lat"
                            :lon="props.studio.location.lon"
                            :zoom="14"
                            :enableMarkerLink="false"
                            class="h-64 border border-slate-400 md:h-96 overflow-clip rounded-xl"
                        />
                    </div>
                </Section>
            </div>
        </div>
        <!-- / -->
    </FrontofficeLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3'
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import Modal from '@/Components/Modal.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import ShowAll from '@/Components/ShowAll.vue';
import Button from '@/Components/Form/Button.vue';
import Gallery from './Gallery.vue';
import TitleBar from './TitleBar.vue';
import Menu from './Menu.vue';
import Section from './Section.vue';
import RoomCard from './RoomCard.vue';
import BundleCard from './BundleCard.vue';

const props = defineProps({
    studio: Object,
    all_photos: Object,
    equipment_categories: Object,
    contacts: Object,
    socials: Object,
    weekdays: Object,
    holydays: Object,
    months: Object,
    request: Object,
});

const isLoading = ref(false);
const openCollabModal = ref(false);
const openRulesModal = ref(false);
const openServicesModal = ref(false);
const openComfortsModal = ref(false);
const openHolydaysListModal = ref(false);

router.on('start', ()=> isLoading.value = true);
router.on('finish', ()=> isLoading.value = false);

const computedContacts = computed(()=>{
    let arrContacts = Object.entries(props.contacts);
    let contacts = [];

    if(arrContacts.length){
        arrContacts.forEach(contact => {
            let label = contact[0];
            let href = contact[1];
            let color = 'orange';
            let icon = null;

            if(href){    
                switch (contact[0]) {
                    case 'phone':
                        label = contact[1];
                        href = 'tel:' + contact[1];
                        icon = 'fa-solid fa-phone';
                    break;
                    case 'email':
                        label = contact[1];
                        href = 'mailto:' + contact[1];
                        icon = 'fa-solid fa-envelope';
                    break;
                    case 'messenger':
                        label = 'Messenger';
                        color = 'text-messenger';
                        icon = 'fa-brands fa-facebook-messenger';
                    break;
                    case 'whatsapp':
                        label = 'Whatsapp';
                        color = 'text-whatsapp';
                        icon = 'fa-brands fa-whatsapp';
    
                    break;
                    case 'telegram':
                        label = 'Telegram';
                        color = 'text-telegram';
                        icon = 'fa-brands fa-telegram';
                    break;
                }
    
                contacts.push({
                    label: label,
                    href: href,
                    color: color,
                    icon: icon,
                });
            }
        });
    }

    return contacts;
});

const links = [
    {
        text: 'presentazione',
        id: '#presentazione',
        enabled: props.studio.description ? true : false
    },
    {
        text: 'collaborazioni',
        id: '#collaborazioni',
        enabled: props.studio.collaborations.length ? true : false
    },
    {
        text: 'comfort',
        id: '#comfort',
        enabled: props.studio.comforts.length ? true : false
    },
    {
        text: 'servizi',
        id: '#servizi',
        enabled: props.studio.services.length ? true : false
    },
    {
        text: 'sale',
        id: '#sale',
        enabled: props.studio.rooms.length ? true : false
    },
    {
        text: 'pacchetti',
        id: '#pacchetti',
        enabled: props.studio.bundles.length ? true : false
    },
    {
        text: 'video',
        id: '#video',
        enabled: props.studio.videos.length ? true : false
    },
    {
        text: 'regolamento',
        id: '#regolamento',
        enabled: props.studio.rule.before || props.studio.rule.during || props.studio.rule.after ? true : false
    },
    {
        text: 'annullamenti',
        id: '#annullamenti',
        enabled: props.studio.cancel_settings.has_cancel_policy
    },
    {
        text: 'orari',
        id: '#orari',
        enabled: props.studio.availability.length ? true : false
    },
    {
        text: 'contatti',
        id: '#contatti',
        enabled: true
    },
    {
        text: 'location',
        id: '#location',
        enabled: props.studio.location.complete_address ? true : false
    },
];

</script>
