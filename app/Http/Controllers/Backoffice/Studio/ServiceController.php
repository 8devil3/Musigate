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
    public function index(): Response
    {
        $services = auth()->user()->studio->services;

        return Inertia::render('Backoffice/Studio/Services/Index', compact('services'));
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('Backoffice/Studio/Services/Edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($request->toArray());

        return back()->with('success', 'Servizio aggiornato correttamente');
    }
}
