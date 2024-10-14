<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RuleController extends Controller
{
    public function edit(): Response
    {
        $rules = auth()->user()->studio->rule;

        return Inertia::render('Backoffice/Studio/Rules', compact('rules'));
    }

    public function update(Request $request): RedirectResponse
    {
        auth()->user()->studio->rule->update($request->toArray());

        return back()->with('success', 'Regolamento salvato');
    }
}
