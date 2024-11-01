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
use App\Models\Studio\CancelPolicySetting;

class CreateStudioService
{
    public static function store(User $user)
    {
        $studio_step1 = session()->get('studio_step1');
        $studio_step2 = session()->get('studio_step2');

        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($studio_step1['name'])),
            'category' => $studio_step1['category'],
            'vat' => $studio_step1['vat'],
        ]);

        //creo la disponibiità settimanale
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
        ] + $studio_step2);

        Rule::create(['studio_id' => $studio->id]);
        Social::create(['studio_id' => $studio->id]);
        Contact::create(['studio_id' => $studio->id]);
        CancelPolicySetting::create(['studio_id' => $studio->id]);
        // BookingSetting::create(['studio_id' => $studio->id]);
    }
}