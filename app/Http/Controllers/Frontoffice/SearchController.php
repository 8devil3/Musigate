<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Room\EquipmentCategory;
use App\Models\Studio\Availability;
use App\Models\Studio\Studio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $equip = request('equip', null);
        $user = auth()->user();

        $studios = Studio::with(['location', 'photos'])
            ->where('is_complete', true)
            ->where('is_visible', true)
            ->when($equip, function($query) use($equip){
                $query->whereRelation('rooms.equipments', 'name', 'LIKE', '%' . strtolower($equip) . '%');
            })
            ->when($user && $user->role_id === Role::STUDIO , function($query) use($user){
                $query->whereNot('id', $user->studio->id);
            })
            ->when(request('name', null), function ($query){
                $query->whereLike('name', '%' . request('name') . '%');
            })
            ->when(request('location', 'all') !== 'all', function($query){
                $query->whereHas('location', function($query){
                    $query->whereLike('province', '%' . request('location'));
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
        if(!$studio->is_complete) abort(404);

        $weekdays = Availability::WEEKDAYS;

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
        ])->load(['rooms' => function($query){
            //mostro solo le sale pubblicate
            $query->with(['equipments', 'photos', 'prices.timeband:id,weekday,name,start,end'])
                ->withMin('prices as min_price', 'price')
                ->withMin('prices as min_discounted_price', 'discounted_price')
                ->where('is_visible', true);
        }])->load(['bundles' => function($query){
            //mostro solo i bundle pubblicati
            $query->with('prices.timeband:id,weekday,name,start,end')
                ->withMin('prices as min_price', 'price')
                ->withMin('prices as min_discounted_price', 'discounted_price')
                ->where('is_visible', true);
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
        
        return Inertia::render('Frontoffice/Studio/Show', compact('request', 'studio', 'equipment_categories', 'socials', 'contacts', 'weekdays', 'all_photos'));
    }
}
