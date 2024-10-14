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
            'name' => ucwords(strtolower($data_step2['studio_name'])),
            // 'category' => $category,
            'vat' => $data_step2['vat'],
        ]);

        //creo la dipsonibiità settimanale
        for ($i = 1; $i <= 7; $i++){
            $start = null;
            $end = null;
            $is_open = false;

            if($i <= 5){
                $start = '10:00';
                $end = '23:00';
                $is_open = true;
            }

            Availability::create([
                'studio_id' => $studio->id,
                'weekday' => $i,
                'is_open' => $is_open,
                'open_start' => $start,
                'open_end' => $end,
            ]);
        }

        Location::create([
            'studio_id' => $studio->id,
            'address' => $data_step2['address'],
            'number' => $data_step2['number'],
            'city' => $data_step2['city'],
            'province' => $data_step2['province'],
            'cap' => $data_step2['cap'],
            'complete_address' => $data_step2['complete_address'],
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