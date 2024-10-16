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
            <div class="space-y-16 md:grow">
                <TitleBar :studio="props.studio" :socials=props.socials :request="props.request" />

                <Section v-if="props.studio.description" title="Presentazione" id="presentazione">
                    <p class="whitespace-pre-wrap">{{ props.studio.description }}</p>
            
                    <div v-if="props.studio.payment_methods.length" class="pt-2 space-y-2">
                        <h3>Pagamenti accettati</h3>
            
                        <ul class="flex flex-wrap gap-2">
                            <li v-for="payment in props.studio.payment_methods">
                                <img :src="'/img/payments/' + payment.img_name" :alt="payment.name" class="h-6 w-9 md:h-8 md:w-11" :title="payment.name">
                            </li>
                        </ul>
                    </div>
                </Section>

                <Section v-if="props.studio.collaborations.length" title="Collaborazioni" id="collaborazioni">
                    <div class="space-y-2">
                        <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-4 md:grid-rows-3 sm:grid-flow-col sm:auto-cols-max sm:gap-x-12">
                            <template v-for="collab, id in props.studio.collaborations">
                                <li v-if="id <= 15" class="list-musigate">
                                    {{ collab.title }}
                                </li>
                            </template>
                        </ul>
                
                        <ShowAll v-if="props.studio.collaborations.length > 15" @click="openCollabModal = true" />
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
                </Section>
            
                <Section v-if="props.studio.comforts.length" title="Comfort" id="comfort">
                    <div class="space-y-2">
                        <ul class="grid grid-cols-1 list-musigate sm:grid-cols-none sm:grid-rows-4 md:grid-rows-3 sm:grid-flow-col sm:auto-cols-max sm:gap-x-12">
                            <template v-for="comfort, id in props.studio.comforts">
                                <li v-if="id <= 15" class="list-musigate">
                                    {{ comfort.name }}
                                </li>
                            </template>
                        </ul>
        
                        <ShowAll v-if="props.studio.comforts.length > 15" @click="openComfortsModal = true" />

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
                </Section>

                <Section v-if="props.studio.rooms.length" title="Sale prova" id="sale-prova">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <RoomCard v-for="room in props.studio.rooms" :room="room" :equipment_categories="props.equipment_categories" :weekdays="props.weekdays" />
                    </div>
                </Section>
            
                <Section v-if="props.studio.rule.before || props.studio.rule.during || props.studio.rule.after" title="Regolamento" id="regolamento">
                    <div class="mb-6">
                        <h3>Prima della sessione</h3>
                        <p class="whitespace-pre-wrap">{{ props.studio.rule.before.substring(0, 300) }}...</p>
                    </div>
            
                    <div class="mb-6">
                        <h3>Durante la sessione</h3>
                        <p class="whitespace-pre-wrap">{{ props.studio.rule.during.substring(0, 300) }}...</p>
                    </div>
            
                    <div>
                        <h3>Dopo la sessione</h3>
                        <p class="whitespace-pre-wrap">{{ props.studio.rule.after.substring(0, 300) }}...</p>
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

                <Section v-if="props.studio.videos.length" title="Video" id="video">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <iframe v-for="video in props.studio.videos" :src="'https://www.youtube.com/embed/' + video.yt_id" frameborder="0" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; web-share" allowfullscreen class="w-full border border-slate-700 rounded-xl aspect-video"></iframe>
                    </div>                    
                </Section>

                <Section title="Contatti" id="contatti">
                    <Button v-if="!props.contacts" @click="router.reload({ only: ['contacts'] })" text="Mostra contatti" :isLoading="isLoading" :disabled="isLoading" />
                    
                    <p v-else-if="!computedContacts.length" class="text-sm text-slate-300">Lo Studio non ha inserito alcun contatto.</p>

                    <ul v-else class="grid grid-cols-1 gap-2 p-0 m-0 list-none sm:grid-cols-3 md:flex md:flex-wrap list-image-none">
                        <li v-for="contact in computedContacts">
                            <Button
                                type="link"
                                :href="contact.href"
                                :text="contact.label"
                                :icon="contact.icon"
                                :color="contact.color"
                                class="w-full"
                            />
                        </li>
                    </ul>
                </Section>

                <Section title="Orari" id="orari">
                    <ul class="grid grid-cols-2 list-none list-image-none gap-x-4 gap-y-8 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7">
                        <li v-for="wd, wdKey in props.weekdays" class="space-y-2">
                            <div>
                                <div class="font-normal" :class="props.studio.availability.find(av => av.weekday == wdKey).is_open ? 'text-white' : 'text-slate-400'">
                                    {{ wd }}
                                </div>
                                <div class="text-xs font-normal" :class="props.studio.availability.find(av => av.weekday == wdKey).is_open ? 'text-green-500' : 'text-red-500'">
                                    {{ props.studio.availability.find(av => av.weekday == wdKey).is_open ? 'aperto' : 'chiuso' }}
                                </div>
                            </div>

                            <div>
                                <template v-if="props.studio.availability.find(av => av.weekday == wdKey).is_open">
                                    <time :datetime="props.studio.availability.find(av => av.weekday == wdKey).open_start">
                                        {{ props.studio.availability.find(av => av.weekday == wdKey).open_start }}
                                    </time>
                                    -
                                    <time :datetime="props.studio.availability.find(av => av.weekday == wdKey).open_end">
                                        {{ props.studio.availability.find(av => av.weekday == wdKey).open_end }}
                                    </time>
                                </template>
                                <template v-else>
                                    -
                                </template>
                            </div>
                        </li>
                    </ul>
                </Section>

                <Section title="Location" id="location">
                    <div class="space-y-6">
                        <div class="flex flex-wrap items-start sm:flex-nowrap gap-x-10 gap-y-4">
                            <div class="sm:shrink-0">
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
                
                        <GoogleMaps :studios="[props.studio]" :lat="props.studio.location.lat" :lon="props.studio.location.lon" :zoom="14" class="h-64 border border-slate-400 md:h-96 overflow-clip rounded-xl" />
                    </div>
                </Section>
            </div>
            <!-- / -->
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
import dayjs from 'dayjs';

const props = defineProps({
    studio: Object,
    all_photos: Object,
    equipment_categories: Object,
    contacts: Object,
    socials: Object,
    weekdays: Object,
    request: Object,
});

const isLoading = ref(false);
const openCollabModal = ref(false);
const openRulesModal = ref(false);
const openServicesModal = ref(false);
const openComfortsModal = ref(false);

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
                        label = 'Telefono';
                        href = 'tel:' + contact[1];
                        icon = 'fa-solid fa-phone';
                    break;
                    case 'email':
                        href = 'mailto:' + contact[1];
                        icon = 'fa-solid fa-envelope';
                    break;
                    case 'messenger':
                        color = 'messenger';
                        icon = 'fa-brands fa-facebook-messenger';
                    break;
                    case 'whatsapp':
                        color = 'whatsapp';
                        icon = 'fa-brands fa-whatsapp';
    
                    break;
                    case 'telegram':
                        color = 'telegram';
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
        enabled: props.studio.services.length || props.studio.comforts.length ? true : false
    },
    {
        text: 'sale prova',
        id: '#sale-prova',
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
