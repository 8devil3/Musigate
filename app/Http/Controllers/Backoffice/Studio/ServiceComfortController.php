<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Comfort;
use App\Models\Studio\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServiceComfortController extends Controller
{
    public function edit(): Response
    {
        $all_services = Service::pluck('name', 'id')->toArray();
        $all_comforts = Comfort::pluck('name', 'id')->toArray();

        $services = auth()->user()->studio->services()->pluck('service_id')->toArray();
        $comforts = auth()->user()->studio->comforts()->pluck('comfort_id')->toArray();

        return Inertia::render('Backoffice/Studio/ServicesComforts', compact('all_services', 'all_comforts', 'services', 'comforts'));
    }

    public function update(Request $request): RedirectResponse
    {
        auth()->user()->studio->services()->sync($request->services);
        auth()->user()->studio->comforts()->sync($request->comforts);

        return redirect()->back();
    }
}
