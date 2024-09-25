<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
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
            'contacts:id,studio_id'
        ]);

        return Inertia::render('Backoffice/Studio/Description', compact('studio'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255|in:Professional,Home',
            'vat' => 'exclude_unless:category,Professional|required|string|size:11',
            'record_label' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
            'desc' => 'required|string|min:100'
        ]);

        $studio = auth()->user()->studio;

        $studio->update([
            'name' => $request->name,
            'category' => $request->category,
            'record_label' => $request->record_label,
            'is_visible' => $request->is_visible,
            'desc' => $request->desc,
        ]);

        if($request->category === 'Professional' && $request->vat){
            $studio->update(['vat' => $request->vat]);
        } else {
            $studio->update(['vat' => null]);
        }

        return redirect()->back();
    }

    public function logo_upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|image|max:1024',
        ]);

        $current_studio = auth()->user()->studio;

        if($current_studio->logo){
            Storage::disk('public')->delete($current_studio->logo);
            $current_studio->update(['logo' => null]);
        }

        $scaled_image = Image::read($request->file)->scale(160, 160);

        $img_path = 'users/user-' . auth()->id() . '/studio/logo/' . \Str::uuid()->toString() . '.png';

        Storage::disk('public')->put($img_path, $scaled_image->toPng());

        $current_studio->update(['logo' => $img_path]);

        return redirect()->back();
    }

    public function logo_delete(): RedirectResponse
    {
        $current_studio = auth()->user()->studio;

        Storage::disk('public')->delete($current_studio->logo);
        
        $current_studio->update(['logo' => null]);

        return redirect()->back();
    }
}
