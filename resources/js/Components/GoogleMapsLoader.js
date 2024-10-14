//questo file è un "singleton": permette di caricare una sola volta un oggetto o una classe

import { Loader } from "@googlemaps/js-api-loader";

let loaderInstance = null;

export const getGoogleMapsLoader = () => {
    // Inizializza il loader solo una volta
    if (!loaderInstance) {
        loaderInstance = new Loader({
            apiKey: import.meta.env.VITE_GOOGLE_API_KEY,
            version: 'quarterly',
            libraries: ['maps', 'marker', 'places'],
            language: 'it',
            region: 'IT',
        });
    }

    return loaderInstance;
};