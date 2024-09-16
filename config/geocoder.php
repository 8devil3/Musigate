<?php

return [

    /*
     * The api key used when sending Geocoding requests to Google.
     */
    'key' => env('VITE_GOOGLE_API_KEY', ''),

    /*
     * The language param used to set response translations for textual data.
     *
     * More info: https://developers.google.com/maps/faq#languagesupport
     */

    'language' => 'it_IT',

    /*
     * The region param used to finetune the geocoding process.
     *
     * More info: https://developers.google.com/maps/documentation/geocoding/requests-geocoding#RegionCodes
     */
    'region' => 'it',

    /*
     * The bounds param used to finetune the geocoding process.
     *
     * More info: https://developers.google.com/maps/documentation/geocoding/requests-geocoding#Viewports
     */
    'bounds' => '',

    /*
     * The country param used to limit results to a specific country.
     *
     * More info: https://developers.google.com/maps/documentation/javascript/geocoding#GeocodingRequests
     */
    'country' => 'it',

];
