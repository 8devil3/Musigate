<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Studio;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $studio = auth()->user()->studio->load([
            'location:id,studio_id,complete_address',
            'contacts'
        ])->loadCount(['availability as count_close_days' => function($query){
            $query->where('open_type', 'close');
        }])->loadCount(['rooms as room_count' => function($query){
            $query->where('is_published', true);
        }])->loadCount(['bundles as bundle_count' => function($query){
            $query->where('is_published', true);
        }])->loadCount(['payment_methods as payment_methods_count', 'photos as photos_count']);

        return Inertia::render('Backoffice/Studio/Dashboard/Dashboard', compact('studio'));
    }
}
