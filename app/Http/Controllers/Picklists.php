<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class Picklists
{
    const OPEN_TYPES = [
        'open' => 'Aperto con orari tradizionali',
        'open_overnight' => 'Aperto con orari overnight',
        'open_h24' => 'Aperto tutto il giorno (24h)',
        'open_forewarning' => 'Aperto su richiesta con preavviso',
        'close' => 'Chiuso',
    ];

    const WEEKDAYS = [
        1 => 'lunedì',
        2 => 'martedì',
        3 => 'mercoledì',
        4 => 'giovedì',
        5 => 'venerdì',
        6 => 'sabato',
        7 => 'domenica',
        8 => 'giorni festivi',
    ];

    const MONTHS = [
        1 => 'Gennaio',
        2 => 'Febbraio',
        3 => 'Marzo',
        4 => 'Aprile',
        5 => 'Maggio',
        6 => 'Giugno',
        7 => 'Luglio',
        8 => 'Agosto',
        9 => 'Settembre',
        10 => 'Ottobre',
        11 => 'Novembre',
        12 => 'Dicembre',
    ];

    const ROOM_PRICE_TYPES = [
        'no_price' => 'Nessuna tariffa',
        'hourly_price' => 'Tariffa oraria',
        'monthly_price' => 'Tariffa mensile',
        'timebands_price' => 'Tariffe a fasce orarie',
    ];

    const BUNDLE_PRICE_TYPES = [
        'no_price' => 'Nessuna tariffa',
        'fixed_price' => 'Tariffa fissa',
        'weekdays_price' => 'Tariffe a giornata',
        'timebands_price' => 'Tariffe a fasce orarie',
    ];

    public static function holydays(): array
    {
        $current_year_easter_date = Carbon::parse(easter_date(now()->year))->locale('it')->timezone(config('app.timezone'))->addDay()->translatedFormat('j F Y') . ': Lunedì di Pasqua ' . now()->year;

        return [
            '01/01' => '1 gennaio: Capodanno',
            '06/01' => '6 gennaio: Epifania',
            'easter' => $current_year_easter_date,
            '25/04' => '25 aprile: Liberazione',
            '01/05' => '1 maggio: Festa del lavoro',
            '02/06' => '2 giugno: Festa della Repubblica',
            '15/08' => '15 agosto: Assunzione di Maria',
            '01/11' => '1 novembre: Ognissanti',
            '08/12' => '8 dicembre: Immacolata Concezione',
            '25/12' => '25 dicembre: Natale',
            '26/12' => '26 Dicembre: Santo Stefano',
        ];
    }
}