<?php

namespace App\Http\Controllers;

class Picklists
{
    const OPEN_TYPES = [
        'open',
        'open_h24',
        'close',
    ];

    const WEEKDAYS = [
        1 => 'Lunedì',
        2 => 'Martedì',
        3 => 'Mercoledì',
        4 => 'Giovedì',
        5 => 'Venerdì',
        6 => 'Sabato',
        7 => 'Domenica',
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
        'timebands_price' => 'Tariffa a fasce orarie',
    ];
}