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
        $studio_data_step2 = session()->get('studio_data_step2');

        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($studio_data_step2['studio_name'])),
            // 'category' => $category,
            'vat' => $studio_data_step2['vat'],
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
            'address' => $studio_data_step2['address'],
            'number' => $studio_data_step2['number'],
            'city' => $studio_data_step2['city'],
            'province' => $studio_data_step2['province'],
            'cap' => $studio_data_step2['cap'],
            'complete_address' => $studio_data_step2['complete_address'],
            'lon' => $studio_data_step2['lon'],
            'lat' => $studio_data_step2['lat']
        ]);

        // BookingSetting::create(['studio_id' => $studio->id]);
        // CancelPolicySetting::create(['studio_id' => $studio->id]);
        Rule::create(['studio_id' => $studio->id]);
        Social::create(['studio_id' => $studio->id]);
        Contact::create(['studio_id' => $studio->id]);
    }
}