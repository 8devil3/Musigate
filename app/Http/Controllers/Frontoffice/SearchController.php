<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Picklists;
use App\Models\Role;
use App\Models\Room\EquipmentCategory;
use App\Models\Studio\Studio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $user = auth()->user();
        $equip = request('equip', null);
        $name = request('name', null);
        $location = request('location', null);

        if($request->category && $request->category !== 'Professional' & $request->category !== 'Home'){
            $request->merge(['category' => null]);
        }

        $studios = Studio::with(['location', 'photos'])
            ->whereNot('id', 6) //escludo lo stduio demo che ho creato col mio account
            ->where('is_complete', true)
            ->where('is_published', true)
            ->when($equip, function($query) use($equip){
                $query->whereRelation('rooms.equipments', 'name', 'LIKE', '%' . strtolower($equip) . '%');
            })
            ->when($user && $user->role_id === Role::STUDIO , function($query) use($user){
                $query->whereNot('id', $user->studio->id);
            })
            ->when($name, function ($query) use($name){
                $query->whereLike('name', '%' . $name . '%');
            })
            ->when($request->category, function ($query) use($request){
                $query->where('category', $request->category);
            })
            ->when($location, function($query) use($location){
                $query->whereHas('location', function($query) use($location){
                    $query->whereLike('province', '%' . $location);
                });
            })
            ->paginate(20)
            ->withQueryString();

        $request = $request->toArray();

        session()->put('request', $request);

        return Inertia::render('Frontoffice/Search/Search', compact('studios', 'request'));
    }

    public function show(Studio $studio): Response
    {
        if(!$studio->is_complete || !$studio->is_published) abort(404);

        $weekdays = Picklists::WEEKDAYS;
        $months = Picklists::MONTHS;
        $holydays = Picklists::holydays();

        $request = session()->get('request');

        $equipment_categories = EquipmentCategory::pluck('name', 'id');

        $studio->load([
            'videos',
            'location',
            'services',
            'comforts',
            'collaborations',
            'rule',
            'payment_methods',
            'availability',
            'cancel_settings',
        ])->load(['rooms' => function($query){
            //mostro solo le sale pubblicate
            $query->with(['equipments', 'photos', 'timeband_prices.timeband:id,weekday,name,start,end', 'hourly_prices'])
                ->withMin('timeband_prices as min_timeband_price', 'price')
                ->withMin('timeband_prices as min_discounted_timeband_price', 'discounted_price')
                ->withMin('hourly_prices as min_hourly_price', 'price')
                ->withMin('hourly_prices as min_discounted_hourly_price', 'discounted_price')
                ->where('is_published', true);
        }])->load(['bundles' => function($query){
            //mostro solo i bundle pubblicati
            $query->with(['timeband_prices.timeband:id,weekday,name,start,end', 'weekday_prices'])
                ->withMin('timeband_prices as min_timeband_price', 'price')
                ->withMin('timeband_prices as min_discounted_timeband_price', 'discounted_price')
                ->withMin('weekday_prices as min_weekday_price', 'price')
                ->withMin('weekday_prices as min_discounted_weekday_price', 'discounted_price')
                ->where('is_published', true);
        }]);

        $room_photos = [];
        if(!empty($studio->rooms)){
            foreach ($studio->rooms as $room) {
                $room_photos[] = $room->photos()->get()->toArray();
            }
        }

        if(!empty($studio->photos)){
            $studio_photos = $studio->photos()->get()->toArray();
        }
        
        $all_photos = array_merge($studio_photos, ...$room_photos);
        $studio_contacts = $studio->contacts->only('email', 'phone', 'telegram', 'messenger', 'whatsapp');
        $socials = $studio->social->only(['facebook', 'instagram', 'youtube', 'linkedin', 'soundcloud', 'spotify', 'itunes', 'website']);
        $contacts = Inertia::lazy(fn () => $studio_contacts);
        
        return Inertia::render('Frontoffice/Studio/Show', compact('request', 'studio', 'equipment_categories', 'socials', 'contacts', 'weekdays', 'holydays', 'months', 'all_photos'));
    }
}
