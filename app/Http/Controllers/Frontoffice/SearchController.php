<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Room\EquipmentCategory;
use App\Models\Room\Room;
use App\Models\Studio\Studio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request): Response
    {
        $radius = request('radius', null);
        $lon = request('location.lon', null);
        $lat = request('location.lat', null);
        $point = $lon && $lat ? "POINT($lon $lat)" : null;

        $start = request('start', null) ? Carbon::parse(request('start')) : null;
        $duration = request('duration', null);
        $end = $start && $duration ? $start->clone()->addHours(intval($duration)) : null;

        $guests = request('guests', null);
        $equip = request('equip', null);
        $user = auth()->user();

        $rooms = Room::with(['photos', 'studio.booking_settings', 'studio.location'])
            ->when($equip, function($query) use($equip){
                $query->whereRelation('equipments', 'name', 'LIKE', '%' . strtolower($equip) . '%');
            })
            ->when($guests, function($query) use($guests){
                $query->where('max_capacity', '>=', intval($guests));
            })
            ->when($start && $start->isAfter(now()->addDay()->startOfDay()) && $duration , function($query) use($start, $end){
                $query->whereHas('bookings', function($query) use($start, $end){
                    $query->whereNotBetween('start', [$start->toDateTimeString(), $end->toDateTimeString()])
                        ->orWhereNotBetween('end', [$start->toDateTimeString(), $end->toDateTimeString()]);
                });
            })
            ->whereHas('studio', function($query) use($user, $radius, $point){
                $query->when($user && $user->role_id === Role::STUDIO , function($query) use($user){
                    $query->whereNot('id', $user->studio->id);
                })
                ->whereIn('category', request('category', ['Professional', 'Home']))
                ->when(request('name', null), function ($query){
                    $query->whereLike('name', '%' . request('name') . '%');
                })
                ->where('is_visible', true)
                ->where('is_complete', true)
                ->when(request('location', 'all') !== 'all' && $point && $radius, function($query) use($point, $radius){
                    $query->whereHas('location', function($query) use($point, $radius){
                        $query->whereRaw('ST_DISTANCE(ST_GeomFromText(CONCAT("POINT(", lon, " ", lat, ")"), 4326), ST_GeomFromText(?, 4326)) <= ?', [$point, $radius * 1000]);
                    });
                });
            })
            ->withCount('studio')
            // ->withMin('rooms as min_price', 'price')
            ->paginate(20)
            ->withQueryString();

        $request = $request->toArray();

        session()->put('request', $request);

        return Inertia::render('Frontoffice/Search/Search', compact('rooms', 'request'));
    }

    public function show(Studio $studio): Response
    {
        if(!$studio->is_visible || !$studio->is_complete) abort(404);

        $request = session('request');

        $equipment_categories = EquipmentCategory::pluck('name', 'id');
        $booking_settings = $studio->booking_settings;

        $studio->load([
            'videos',
            'location',
            'services',
            'comforts',
            'collaborations',
            'rule',
            'payment_methods',
        ])->load(['rooms' => function($query){
            //mostro solo le sale pubblicate
            $query->with(['equipments', 'photos'])
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
        $socials = $studio->socials->only(['facebook', 'instagram', 'youtube', 'linkedin', 'soundcloud', 'spotify', 'itunes', 'website']);
        $contacts = Inertia::lazy(fn () => $studio_contacts);
        
        return Inertia::render('Frontoffice/Studio/Show', compact('request', 'studio', 'equipment_categories', 'booking_settings', 'socials', 'contacts', 'all_photos'));
    }
}
