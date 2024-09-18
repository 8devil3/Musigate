<?php

namespace App\Services;

use Google_Service_Calendar;

class GoogleCalendarAPIService
{
    public static function get_calendars(string $token = null): array
    {
        $calendar_list = [];

        if($token){
            try {
                $client = new \Google_Client();
                $client->setAccessToken($token);
        
                // Crea un servizio di Google Calendar
                $service = new Google_Service_Calendar($client);
        
                // Ottieni la lista dei calendari dell'utente
                $calendar_list = $service->calendarList->listCalendarList()->items;
            } catch (\Throwable $th) {
                abort(500, $th->getMessage());
                \Log::error('Errore recupero lista calendari: ' . $th->getMessage());
            }
        }

        return $calendar_list;
    }
}