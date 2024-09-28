<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Comfort;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ComfortController extends Controller
{
    public function edit(): Response
    {
        $all_comforts = Comfort::pluck('name', 'id')->toArray();
        $comforts = auth()->user()->studio->comforts()->pluck('comfort_id')->toArray();

        return Inertia::render('Backoffice/Studio/Comforts', compact('all_comforts', 'comforts'));
    }

    public function update(Request $request): RedirectResponse
    {
        auth()->user()->studio->comforts()->sync($request->comforts);

        return redirect()->back();
    }
}
