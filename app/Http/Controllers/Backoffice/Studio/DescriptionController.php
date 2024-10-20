<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Inertia\Inertia;
use Inertia\Response;

class DescriptionController extends Controller
{
    public function edit(): Response
    {
        $studio = auth()->user()->studio->load([
            'location:id,studio_id,complete_address',
            'payment_methods',
            'photos:id,studio_id',
            'contacts'
        ]);

        return Inertia::render('Backoffice/Studio/Description', compact('studio'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'category' => 'required|string|max:255|in:Professional,Home',
            'vat' => 'required|string|size:11',
            'is_record_label' => 'boolean',
            'is_visible' => 'boolean',
            'description' => 'required|string|min:100'
        ]);

        $studio = auth()->user()->studio;

        $studio->update($request->toArray());

        // if($request->category !== 'Professional'){
        //     $studio->update(['vat' => null]);
        // }

        return back()->with('success', 'Descrizione salvata');
    }

    public function logo_upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'image|max:1024',
        ]);

        $studio = auth()->user()->studio;

        if($studio->logo){
            Storage::disk('public')->delete($studio->logo);
            $studio->update(['logo' => null]);
        }

        $image_manager = ImageManager::gd();
        $new_image = $image_manager->create(160, 160)->fill('fff');
        $scaled_logo = $image_manager->read($request->file)->scale(150, 150);
        $new_image->place($scaled_logo, 'center');

        $path = 'studios/studio-' . $studio->id . '/logo/' . \Str::uuid() . '.png';

        Storage::disk('public')->put($path, $new_image->toPng());

        $studio->update(['logo' => $path]);

        return back()->with('success', 'Logo caricato');
    }

    public function logo_delete(): RedirectResponse
    {
        $studio = auth()->user()->studio;

        Storage::disk('public')->delete($studio->logo);
        
        $studio->update(['logo' => null]);

        return back()->with('success', 'Logo eliminato');
    }
}
