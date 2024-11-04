<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Studio\Studio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $latest_studios = Studio::with(['location', 'photos'])
            ->whereNot('id', 6) //escludo lo stduio demo che ho creato col mio account
            ->where('is_complete', true)
            ->where('is_published', true)
            ->limit(6)
            ->latest()
            ->get();

        return Inertia::render('Frontoffice/Home', compact('latest_studios'));
    }
}
