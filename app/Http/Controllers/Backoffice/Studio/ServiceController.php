<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    private $services;

    public function __construct()
    {
        $this->services = Service::where('studio_id', auth()->user()->studio->id);
    }

    public function index(): Response
    {
        $services = $this->services->get();

        return Inertia::render('Backoffice/Studio/Services/Index', compact('services'));
    }

    public function create(): Response
    {
        $service = [];

        return Inertia::render('Backoffice/Studio/Services/CreateEdit', compact('service'));
    }

    public function store(Request $request): RedirectResponse
    {
        $service = auth()->user()->studio->create($request->toArray());

        return to_route('services.edit', $service->id)->with('success', 'Servizio salvato');
    }

    public function edit(int $service_id): Response
    {
        $service = $this->services->findOrFail($service_id);

        return Inertia::render('Backoffice/Studio/Services/CreateEdit', compact('service'));
    }

    public function update(Request $request, int $service_id): RedirectResponse
    {
        $this->services->findOrFail($service_id)->update($request->toArray());

        return back()->with('success', 'Servizio aggiornato');
    }

    public function destroy(int $service_id): RedirectResponse
    {
        $this->services->findOrFail($service_id)->delete();

        return back()->with('success', 'Servizio eliminato');
    }
}
