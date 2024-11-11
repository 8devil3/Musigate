<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Services\CheckStudioInfo;
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
            'contacts'
        ])->loadCount(['availability as count_close_days' => function($query){
            $query->where('open_type', 'close');
        }])->loadCount(['rooms as room_count' => function($query){
            $query->where('is_published', true);
        }])->loadCount(['bundles as bundle_count' => function($query){
            $query->where('is_published', true);
        }])->loadCount(['payment_methods as payment_methods_count', 'photos as photos_count']);

        return Inertia::render('Backoffice/Studio/Description', compact('studio'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:240',
            'category' => 'required|string|max:255|in:Professional,Home',
            'vat' => 'nullable|required_if:category,Professional|string|size:11',
            'is_record_label' => 'boolean',
            'is_published' => 'boolean',
            'description' => 'required|string|min:100'
        ]);

        $studio = auth()->user()->studio;

        $studio->update($request->toArray());

        if($request->category !== 'Professional'){
            $studio->update(['vat' => null]);
        }

        CheckStudioInfo::update_studio($studio);

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
        $new_image = $image_manager->create(160, 160)->fill('030712');
        $scaled_logo = $image_manager->read($request->file)->scale(160, 160);
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
