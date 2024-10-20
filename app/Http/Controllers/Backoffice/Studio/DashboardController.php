<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $studio = auth()->user()->studio->load([
            'rooms:id,studio_id,is_visible',
            'location:id,studio_id,complete_address',
            'payment_methods',
            'photos:id,studio_id',
            'contacts'
        ]);

        return Inertia::render('Backoffice/Studio/Dashboard/Dashboard', compact('studio'));
    }
}
