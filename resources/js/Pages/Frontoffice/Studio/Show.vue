<template>
    <FrontofficeLayout :title="props.studio.name">
        <!-- galleria -->
        <Gallery :imgs="imgs" />
        <!-- / -->
        
        <!-- menu orizzontale -->
        <ListingMenu :links="links" />
        <!-- / -->

        <!-- contenuto -->
        <div class="w-full gap-16 px-4 mx-auto max-w-7xl md:flex md:items-start">
            <!-- sezioni -->
            <div class="py-8 space-y-20 lg:py-12 md:grow">
                <TitleBar :studio="props.studio" :user="props.user" :request="props.request" />

                <ListingSection v-if="props.studio.desc" title="Lo Studio" id="studio">
                    <p>{{ props.studio.desc }}</p>
            
                    <div v-if="props.studio.payment_methods.length" class="pt-2 space-y-2">
                        <h3>Pagamenti accettati</h3>
            
                        <ul class="flex flex-wrap gap-2">
                            <li v-for="payment in props.studio.payment_methods">
                                <img :src="'/img/payments/' + payment.img_name" :alt="payment.name" class="h-6 md:h-8" :title="payment.name">
                            </li>
                        </ul>
                    </div>
                </ListingSection>

                <ListingSection v-if="props.studio.collaborations.length" title="Collaborazioni" id="collaborazioni">
                    <div class="space-y-2">
                        <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-4 md:grid-rows-3 sm:grid-flow-col sm:auto-cols-max sm:gap-x-12">
                            <template v-for="collab, id in props.studio.collaborations">
                                <li v-if="id < 12" class="list-musigate whitespace-nowrap">
                                    {{ collab.title }}
                                </li>
                            </template>
                        </ul>
                
                        <ShowAll @click="openCollabModal = true" />
                    </div>

                    <Modal :isOpen="openCollabModal" @close="openCollabModal = false">
                        <template #title>
                            Collaborazioni
                        </template>

                        <template #description>
                            <ul class="relative ml-1.5 border-l-2 border-orange-500">                  
                                <li v-for="collab, id in props.studio.collaborations" class="ml-4" :class="id == props.studio.collaborations.length -1 ? '' : ' mb-8'">
                                    <div class="absolute w-3.5 h-3.5 bg-zinc-900 rounded-full mt-1 -left-2 border-2 border-orange-500"></div>
                                    <h4 class="my-1">{{ collab.title }}</h4>
                                    <p v-if="collab.desc" class="text-sm text-white">{{ collab.desc }}</p>
                                </li>
                            </ul>
                        </template>
                    </Modal>
                </ListingSection>
            
                <ListingSection v-if="props.studio.services.length || props.studio.comforts.length" title="Servizi & Comfort" id="servizi-comfort">
                    <div class="grid grid-flow-row md:grid-flow-col auto-rows-min lg:grid-rows-1 gap-y-6">

                        <div v-if="props.studio.services.length" class="space-y-4 md:space-y-2">
                            <h3>Servizi</h3>
                            <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-3 sm:grid-flow-col sm:auto-cols-max sm:gap-x-12">
                                <template v-for="service, id in props.studio.services">
                                    <li v-if="id < 6" class="list-musigate whitespace-nowrap">
                                        {{ service.name }}
                                    </li>
                                </template>
                            </ul>
            
                            <ShowAll v-if="props.studio.services.length > 6" @click="openServicesModal = true" />

                            <Modal :isOpen="openServicesModal" @close="openServicesModal = false">
                                <template #title>
                                    Servizi
                                </template>

                                <template #description>
                                    <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-4 sm:grid-flow-col sm:auto-cols-max sm:gap-x-16">
                                        <li v-for="service in props.studio.services" class="list-musigate whitespace-nowrap">
                                            {{ service.name }}
                                        </li>
                                    </ul>
                                </template>
                            </Modal>
                        </div>
            
                        <div v-if="props.studio.comforts.length" class="space-y-4 md:space-y-2">
                            <h3>Comforts</h3>
                            <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-3 sm:grid-flow-col sm:auto-cols-max sm:gap-x-12">
                                <template v-for="comfort, id in props.studio.comforts">
                                    <li v-if="id < 6" class="list-musigate whitespace-nowrap">
                                        {{ comfort.name }}
                                    </li>
                                </template>
                            </ul>
            
                            <ShowAll v-if="props.studio.comforts.length > 6" @click="openComfortsModal = true" />

                            <Modal :isOpen="openComfortsModal" @close="openComfortsModal = false">
                                <template #title>
                                    Comfort
                                </template>

                                <template #description>
                                    <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-4 sm:grid-flow-col sm:auto-cols-max sm:gap-x-16">
                                        <li v-for="comfort in props.studio.comforts" class="list-musigate whitespace-nowrap">
                                            {{ comfort.name }}
                                        </li>
                                    </ul>
                                </template>
                            </Modal>
                        </div>
                    </div>
                </ListingSection>

                <ListingSection v-if="props.studio.rooms.length" title="Sale Studio" id="sale-studio">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <ListingRoomCard v-for="room in props.studio.rooms" :room="room" :roomTypes="props.room_types" :equipment_categories="props.equipment_categories"/>
                    </div>
                </ListingSection>
            
                <ListingSection v-if="props.studio.rule.before || props.studio.rule.during || props.studio.rule.after" title="Regolamento" id="regolamento">
                    <div class="mb-6">
                        <h3>Prima della sessione</h3>
                        <p>{{ props.studio.rule.before.substring(0, 300) }}...</p>
                    </div>
            
                    <div class="mb-6">
                        <h3>Durante la sessione</h3>
                        <p>{{ props.studio.rule.during.substring(0, 300) }}...</p>
                    </div>
            
                    <div>
                        <h3>Dopo la sessione</h3>
                        <p>{{ props.studio.rule.after.substring(0, 300) }}...</p>
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
                </ListingSection>

                <ListingSection v-if="props.studio.videos.length" title="Video" id="video">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <iframe v-for="video in props.studio.videos" :src="'https://www.youtube.com/embed/' + video.yt_id" frameborder="0" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; web-share" allowfullscreen class="w-full border border-gray-700 rounded-xl aspect-video"></iframe>
                    </div>                    
                </ListingSection>
                
                <ListingSection title="Location" id="location">
                    <div class="flex flex-wrap items-start sm:flex-nowrap gap-x-10 gap-y-6">
                        <div class="shrink-0">
                            <h3>Indirizzo</h3>
                            <address class="capitalize">
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
                            <h3>Come arrivare</h3>
                            <p>{{ props.studio.location.notes }}</p>
                        </div>
                    </div>
            
                    <GoogleMaps :studios="[props.studio]" :lat="props.studio.location.lat" :lon="props.studio.location.lon" :zoom="14" class="h-64 border border-gray-400 md:h-96 overflow-clip rounded-xl" />
                </ListingSection>
            </div>
            <!-- / -->

            <!-- contatti desktop -->
            <div class="sticky flex-col hidden w-48 gap-4 pt-6 text-center lg:flex top-12 shrink-0">
                <div class="pb-2 border-b border-orange-500">
                    <h3 class="m-0">Contatta lo Studio</h3>
                </div>

                <Button v-if="!props.contacts" @click="router.reload({ only: ['contacts'] })" text="Mostra contatti" />

                <div v-else-if="!props.contacts.email && !props.contacts.phone && !props.contacts.telegram && !props.contacts.messenger && !props.contacts.whatsapp" class="text-sm text-gray-300">Lo Studio non ha inserito alcun contatto.</div>

                <template v-else>
                    <a v-if="props.contacts.email" :href="'mailto:' + props.contacts.email" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all bg-orange-500 border-0 rounded-full shadow-md whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                        <i class="text-sm leading-none text-white fa-solid fa-envelope"></i>
                        Email
                    </a>

                    <a v-if="props.contacts.phone" :href="'tel:' + props.contacts.phone" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all bg-orange-500 border-0 rounded-full shadow-md whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                        <i class="text-sm leading-none text-white fa-solid fa-phone"></i>
                        Telefono
                    </a>

                    <a v-if="props.contacts.telegram" :href="props.contacts.telegram" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-telegram whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                        <i class="text-sm leading-none text-white fa-brands fa-telegram"></i>
                        Telegram
                    </a>

                    <a v-if="props.contacts.messenger" :href="props.contacts.messenger" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-messenger whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                        <i class="text-sm leading-none text-white fa-brands fa-facebook-messenger"></i>
                        Messenger
                    </a>

                    <a v-if="props.contacts.whatsapp" :href="props.contacts.whatsapp" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-whatsapp whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                        <i class="text-sm leading-none text-white fa-brands fa-whatsapp"></i>
                        Whatsapp
                    </a>
                </template>
            </div>
            <!-- / -->
        </div>
        <!-- / -->
    </FrontofficeLayout>

    <!-- contatti mobile -->
    <div class="sticky inset-x-0 bottom-0 p-4 border-t border-gray-700 md:hidden bg-gray-950/10 backdrop-blur-md">
        <Button @click="mobileContacts()" text="Contatta lo Studio" />
    </div>
    <!-- / -->

    <Modal :isOpen="openContactsModal" @close="openContactsModal = false">
        <template #title>
            Contatta lo Studio
        </template>

        <template #description>
            <div v-if="!props.contacts.email && !props.contacts.phone && !props.contacts.telegram && !props.contacts.messenger && !props.contacts.whatsapp" class="text-sm text-gray-300">Lo Studio non ha inserito alcun contatto.</div>

            <div v-else-if="props.contacts" class="flex flex-col justify-center h-full gap-4">
                <a v-if="props.contacts.email" :href="'mailto:' + props.contacts.email" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all bg-orange-500 border-0 rounded-full shadow-md whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                    <i class="text-sm leading-none text-white fa-solid fa-envelope"></i>
                    Email
                </a>
    
                <a v-if="props.contacts.phone" :href="'tel:' + props.contacts.phone" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all bg-orange-500 border-0 rounded-full shadow-md whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                    <i class="text-sm leading-none text-white fa-solid fa-phone"></i>
                    Telefono
                </a>
    
                <a v-if="props.contacts.telegram" :href="props.contacts.telegram" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-telegram whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                    <i class="text-sm leading-none text-white fa-brands fa-telegram"></i>
                    Telegram
                </a>
    
                <a v-if="props.contacts.messenger" :href="props.contacts.messenger" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-messenger whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                    <i class="text-sm leading-none text-white fa-brands fa-facebook-messenger"></i>
                    Messenger
                </a>
    
                <a v-if="props.contacts.whatsapp" :href="props.contacts.whatsapp" class="inline-flex items-center justify-center h-8 gap-2 px-4 py-0 font-sans text-sm font-medium leading-none text-center text-white capitalize transition-all border-0 rounded-full shadow-md bg-whatsapp whitespace-nowrap disabled:opacity-70 disabled:cursor-not-allowed hover:brightness-110 focus:outline-0 focus:ring-0 active:border-0 focus:border-0">
                    <i class="text-sm leading-none text-white fa-brands fa-whatsapp"></i>
                    Whatsapp
                </a>
            </div>
        </template>
    </Modal>
    <!-- / -->

    <!-- scroll to top -->
    <button type="button" title="Torna su" @click="scrollToTop()" class="fixed flex items-center justify-center w-8 text-sm text-center bg-orange-500 rounded-full shadow-md bottom-4 right-4 md:right-8 md:text-lg md:w-10 aspect-square">
        <i class="leading-none text-white fa-solid fa-chevron-up"></i>
    </button>
    <!-- / -->
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3'
import FrontofficeLayout from '@/Layouts/FrontofficeLayout.vue';
import Modal from '@/Components/Modal.vue';
import GoogleMaps from '@/Components/GoogleMaps.vue';
import ShowAll from '@/Components/ShowAll.vue';
import Button from '@/Components/Form/Button.vue';
import Gallery from '@/Components/Frontoffice/Show/Gallery.vue';
import TitleBar from '@/Components/Frontoffice/Show/TitleBar.vue';
import ListingMenu from '@/Components/Frontoffice/Show/ListingMenu.vue';
import ListingSection from '@/Components/Frontoffice/Show/ListingSection.vue';
import ListingRoomCard from '@/Components/Frontoffice/Show/ListingRoomCard.vue';

const props = defineProps({
    studio: Object,
    room_imgs: Array,
    room_types: Object,
    equipment_categories: Object,
    user: Object,
    contacts: [Object, String],
    weekdays: Object,
    request: Object
});

const openCollabModal = ref(false);
const openRulesModal = ref(false);
const openServicesModal = ref(false);
const openComfortsModal = ref(false);
const openContactsModal = ref(false);

//pulsante torna su
const scrollToTop = ()=>{
    const app = document.querySelector('#app');
    app.scrollTo(0,0);
}

//contatti mobile
const mobileContacts = ()=>{
    router.reload({
        only: ['contacts'],
        onFinish: ()=>{
            openContactsModal.value = true;
        }
    });
}

//rimappo le immagini per la galleria
const imgs = computed(()=>{
    if(props.studio.photos.length){
        return props.studio.photos.sort(photo => photo.is_featured ? -1 : 1).map(photo => photo.path).flat();
    } else {
        return [];
    }
});

const links = [
    {
        text: 'studio',
        id: '#studio',
        enabled: props.studio.desc ? true : false
    },
    {
        text: 'collaborazioni',
        id: '#collaborazioni',
        enabled: props.studio.collaborations.length ? true : false
    },
    {
        text: 'servizi & comfort',
        id: '#servizi-comfort',
        enabled: props.studio.services.length || props.studio.comforts.length ? true : false
    },
    {
        text: 'sale studio',
        id: '#sale-studio',
        enabled: props.studio.rooms.length ? true : false
    },
    {
        text: 'regolamento',
        id: '#regolamento',
        enabled: props.studio.rule.before || props.studio.rule.during || props.studio.rule.after ? true : false
    },
    {
        text: 'video',
        id: '#video',
        enabled: props.studio.videos.length ? true : false
    },
    {
        text: 'location',
        id: '#location',
        enabled: props.studio.location.complete_address ? true : false
    },
];

const months = {
    1: "Gennaio",
    2: "Febbraio",
    3: "Marzo",
    4: "Aprile",
    5: "Maggio",
    6: "Giugno",
    7: "Luglio",
    8: "Agosto",
    9: "Settembre",
    10: "Ottobre",
    11: "Novembre",
    12: "Dicembre",
};

</script>
