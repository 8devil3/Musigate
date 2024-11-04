<template>
    <div ref="htmlMap" />
</template>

<script setup>
import { ref } from 'vue';
import { getGoogleMapsLoader } from '@/Components/GoogleMapsLoader.js';

const props = defineProps({
    studios: Object,
    lat: {
        type: Number,
        default: 42.94792427979087
    },
    lon: {
        type: Number,
        default: 12.591507400963922
    },
    zoom: {
        type: Number,
        default: 5
    },
    enableMarkerLink: {
        type: Boolean,
        default: true
    },
});

//Google Places API
const htmlMap = ref(null);

const options = {
    zoom: props.zoom,
    center: {
        lat: parseFloat(props.lat),
        lng: parseFloat(props.lon),
    },
    mapTypeIds: ['roadmap'],
    mapTypeControl: false,
    streetViewControl: false,
    mapId: 'MUSIGATE-MAP-' + Math.ceil(Math.random()* 1000),
};

getGoogleMapsLoader().load().then(async () => {
    const { Map, InfoWindow } = await google.maps.importLibrary("maps"); // Carica la libreria "maps"
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker"); // Carica la libreria "marker"
    
    const map = new google.maps.Map(htmlMap.value, options);
    
    props.studios.forEach(studio => {
        const svgImg = document.createElement("img");
        svgImg.src = '/img/geomarker/geo_default.svg';
        const svgPinElement = new PinElement({glyph: svgImg,});

        const marker = new AdvancedMarkerElement({
            map: map,
            content: svgPinElement.element,
            title: studio.name ?? 'Musigate',
            gmpClickable: true,
            position: {
                lat: studio.location.lat,
                lng: studio.location.lon
            },
        });
        
        const contentString = '<div><div class="text-xs font-medium uppercase text-slate-500">' + studio.category + ' Studio</div><div class="text-base font-bold text-slate-950">' + studio.name + '</div><a href="' + route('studio.show', studio.slug) + '" class="block font-medium text-orange-500 transition-colors hover:text-orange-400">Vai allo Studio</a></div>';

        if(props.enableMarkerLink){
            const infoWindow = new InfoWindow();
    
            marker.addListener('click', ({ domEvent }) => {
                const { target } = domEvent;
                map.setZoom(12);
                map.setCenter(marker.position);
                infoWindow.close();
                infoWindow.setContent(contentString);
                infoWindow.open(marker.map, marker);
            });
        }
    });
});

</script>
