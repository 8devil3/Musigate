<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Comfort;
use App\Models\Studio\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ComfortServiceController extends Controller
{
    public function edit(): Response
    {
        $studio = auth()->user()->studio;

        $all_comforts = Comfort::pluck('name', 'id');
        $comforts = $studio->comforts()->pluck('comfort_id');

        $all_services = Service::pluck('name', 'id');
        $services = $studio->services()->pluck('service_id');

        return Inertia::render('Backoffice/Studio/ComfortsServices', compact('all_comforts', 'comforts', 'all_services', 'services'));
    }

    public function update(Request $request): RedirectResponse
    {
        $studio = auth()->user()->studio;

        $studio->comforts()->sync($request->comforts);
        $studio->services()->sync($request->services);

        return back()->with('success', 'Comfort e servizi salvati');
    }
}
