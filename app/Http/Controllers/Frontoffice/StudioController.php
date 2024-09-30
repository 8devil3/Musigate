<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Room\EquipmentCategory;
use App\Models\Studio\Studio;
use App\Models\Studio\Comfort;
use App\Models\Studio\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudioController extends Controller
{
    public function index(Request $request): Response
    {
        //TODO: aggiungere validazioni input

        $services = Service::pluck('name', 'id');
        $comforts = Comfort::pluck('name', 'id');

        $lon = request('location.lon');
        $lat = request('location.lat');
        $radius = request('radius', 500);

        $point = "POINT($lon $lat)";

        $user = auth()->user();

        $studios = Studio::with(['location', 'photos', 'services', 'comforts', 'videos'])
            ->when($user && $user->role_id === Role::STUDIO , function($query) use($user){
                $query->whereNot('id', $user->studio->id);
            })
            ->withMin('rooms as min_price', 'price')
            ->whereIn('category', request('category', ['Professional', 'Home']))
            ->when(request('name', null), function ($query){
                $query->whereLike('name', '%' . request('name', null) . '%');
            })
            // ->when(request('services', []), function ($query){
            //     $query->whereHas('services', function($query){
            //         $query->whereIn('services.id', request('services', []));
            //     });
            // })
            // ->when(request('comforts', []), function($query){
            //     $query->whereHas('comforts', function($query){
            //         $query->whereIn('comforts.id', request('comforts', []));
            //     });
            // })
            // ->when(request('equip', []), function($query){
            //     $query->whereRelation('rooms.equipments', 'name', 'LIKE', '%' . strtolower(request('equip', [])) . '%');
            // })
            ->where('is_visible', true)
            ->where('is_visible', true)
            ->where('is_complete', true)
            ->whereHas('location', function($query) use($point, $radius){
                $query->whereRaw('ST_DISTANCE(ST_GeomFromText(CONCAT("POINT(", lon, " ", lat, ")"), 4326), ST_GeomFromText(?, 4326)) <= ?', [$point, $radius * 1000]);
            })
            ->join('locations', 'studios.id', 'locations.studio_id', )
            ->orderByRaw('ST_DISTANCE(ST_GeomFromText(CONCAT("POINT(", lon, " ", lat, ")"), 4326), ST_GeomFromText(?, 4326)) ASC', [$point])
            ->paginate(20)
            ->withQueryString();

        $request->toArray();

        session()->put('request', $request->toArray());

        return Inertia::render('Frontoffice/Search/Search', compact('studios', 'services', 'comforts', 'request'));
    }

    public function show(Studio $studio): Response
    {
        if(!$studio->is_visible || !$studio->is_complete) abort(404);

        $request = session('request');

        $equipment_categories = EquipmentCategory::pluck('name', 'id');
        $booking_settings = $studio->booking_settings;

        $user = $studio->user->only('first_name', 'last_name', 'avatar');

        $studio->load([
            'videos',
            'location',
            'services',
            'comforts',
            'collaborations',
            'rule',
            'social',
            'payment_methods'
        ])->load(['rooms' => function($query){
            //mostro solo le sale pubblicate
            $query->with(['equipments', 'photos'])->where('is_visible', true);
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

        $contacts = Inertia::lazy(fn () => $studio->contacts->only('email', 'phone', 'telegram', 'messenger', 'whatsapp'));
        
        return Inertia::render('Frontoffice/Studio/Show', compact('request', 'studio', 'user', 'equipment_categories', 'booking_settings', 'contacts', 'all_photos'));
    }
}
