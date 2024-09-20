<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Room\EquipmentCategory;
use App\Models\Studio\Studio;
use App\Models\Studio\Comfort;
use App\Models\Studio\Service;
use App\Models\Room\RoomType;
use Carbon\Carbon;
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

        $studios = Studio::with(['location', 'photos', 'services', 'comforts', 'videos'])
            ->withMin('rooms as min_price', 'min_price')
            ->whereIn('category', request('category', ['Professional', 'Home']))
            ->when(request('name', null), function ($query){
                $query->whereLike('name', '%' . request('name', null) . '%');
            })
            // ->whereHas('rooms.bookings', function($query){
            //     $query->whereNotBetween('start', [Carbon::parse(request('date') . 'T' . request('time'))->toDateTimeString(), Carbon::parse(request('date') . 'T' . request('time'))->addHours(intval(request('duration')))->toDateTimeString()])
            //     ->whereNotBetween('end', [Carbon::parse(request('date') . 'T' . request('time'))->toDateTimeString(), Carbon::parse(request('date') . 'T' . request('time'))->addHours(intval(request('duration')))->toDateTimeString()]);
            // })
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

        return Inertia::render('Frontoffice/Search', compact('studios', 'services', 'comforts', 'request'));
    }

    public function show(Studio $studio): Response
    {
        if(!$studio->is_visible || !$studio->is_complete) abort(404);

        $request = session('request');

        $room_types = RoomType::pluck('name', 'id');
        $equipment_categories = EquipmentCategory::pluck('name', 'id');
        $booking_settings = $studio->booking_settings;

        $user = $studio->user->only('first_name', 'last_name', 'avatar');

        $studio->load([
            'photos',
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
            $query->where('room_status_id', 4)->with(['equipments', 'photos']);
        }]);

        $contacts = Inertia::lazy(fn () => $studio->contacts->only('email', 'phone', 'telegram', 'messenger', 'whatsapp'));
        
        return Inertia::render('Frontoffice/Studio/Show', compact('request', 'studio', 'user', 'room_types', 'equipment_categories', 'booking_settings', 'contacts'));
    }
}
