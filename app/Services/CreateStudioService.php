<?php

namespace App\Services;
use App\Models\User;
use App\Models\Studio\Rule;
use App\Models\Studio\Social;
use App\Models\Studio\Studio;
use App\Models\Studio\Contact;
use App\Models\Studio\Location;
use App\Models\Studio\Availability;
// use App\Models\Studio\BookingSetting;
// use App\Models\Studio\CancelPolicySetting;

class CreateStudioService
{
    public static function store(User $user)
    {
        $data_step2 = session()->get('data_step2');

        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($data_step2['name'])),
            'category' => $data_step2['category'],
            'vat' => $data_step2['vat'],
        ]);

        //creo la dipsonibiità settimanale
        for ($i = 1; $i <= 7; $i++){
            $open_type = 'close';
            $start = null;
            $end = null;
            
            if($i < 7){
                $open_type = 'open';
                $start = '10:00';
                $end = '23:00';
            }

            Availability::create([
                'studio_id' => $studio->id,
                'weekday' => $i,
                'open_type' => $open_type,
                'open_start' => $start,
                'open_end' => $end,
            ]);
        }

        Location::create([
            'studio_id' => $studio->id,
            'complete_address' => $data_step2['complete_address'],
            'address' => $data_step2['address'],
            'number' => $data_step2['number'],
            'city' => $data_step2['city'],
            'province' => $data_step2['province'],
            'cap' => $data_step2['cap'],
            'lon' => $data_step2['lon'],
            'lat' => $data_step2['lat']
        ]);

        // BookingSetting::create(['studio_id' => $studio->id]);
        // CancelPolicySetting::create(['studio_id' => $studio->id]);
        Rule::create(['studio_id' => $studio->id]);
        Social::create(['studio_id' => $studio->id]);
        Contact::create(['studio_id' => $studio->id]);
    }
}