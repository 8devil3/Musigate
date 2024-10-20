<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Bundle;
use App\Models\Studio\BundlePrice;
use App\Models\Studio\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BundlePriceController extends Controller
{
    public function edit(Bundle $bundle): Response
    {
        $price_types = BundlePrice::PRICE_TYPES;
        $studio = auth()->user()->studio;

        $open_weekdays = $studio->availability()->whereNot('open_type', 'close')->get()
            ->mapWithKeys(function($av): array {
                return [$av->weekday => [
                    'label' => Availability::WEEKDAYS[$av->weekday],
                    'availability_id' => $av->id,
                ]];
            });

        $timebands = $studio->timebands;
        $timeband_prices = $bundle->prices;

        return Inertia::render('Backoffice/Studio/Bundles/Prices', compact('bundle', 'timeband_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $request->validate([
            'price_type' => ['required', 'string', 'in:' . implode(',', array_keys(BundlePrice::PRICE_TYPES))],
        ]);

        $price_type = $request->price_type;

        if($price_type === 'no_price'){
            $bundle->update([
                'price_type' => $price_type,
                'fixed_price' => null,
                'has_discounted_fixed_price' => false,
                'discounted_fixed_price' => null,
                'is_bookable' => false,
            ]);

            $bundle->prices()->delete();
        } else if($price_type === 'fixed_price'){
            $request->validate([
                'fixed_price' => ['required', 'integer', 'min:2'],
                'has_discounted_fixed_price' => ['boolean'],
                'discounted_fixed_price' => ['nullable', 'required_if:has_discounted_fixed_price,accepted', 'integer', 'min:1', 'lt:fixed_price'],
            ]);

            $bundle->update([
                'price_type' => $price_type,
                'fixed_price' => $request->fixed_price,
                'has_discounted_fixed_price' => boolval($request->has_discounted_fixed_price),
                'discounted_fixed_price' => boolval($request->has_discounted_fixed_price) ? $request->discounted_fixed_price : null,
            ]);

            $bundle->prices()->delete();
        } else if($price_type === 'timebands_price'){
            $request->validate([
                'timeband_prices' => ['nullable', 'required_if:price_type,timebands_price', 'array'],
                'timeband_prices.*.timeband_id' => ['required', 'integer', 'exists:timebands,id'],
                'timeband_prices.*.price' => ['required', 'integer', 'min:2'],
                'timeband_prices.*.has_discounted_price' => ['boolean'],
                'timeband_prices.*.discounted_price' => ['nullable', 'required_if:timeband_prices.*.has_discounted_price,accepted', 'integer', 'min:1', 'lt:timeband_prices.*.price'],
            ]);

            $bundle->update([
                'price_type' => $price_type,
                'fixed_price' => null,
                'has_discounted_fixed_price' => false,
                'discounted_fixed_price' => null,
            ]);

            foreach ($request->timeband_prices as $tbp) {
                $bundle->prices()->updateOrCreate([
                    'id' => $tbp['id'],
                ], [
                    'timeband_id' => $tbp['timeband_id'],
                    'price' => $tbp['price'],
                    'has_discounted_price' => boolval($tbp['has_discounted_price']),
                    'discounted_price' => boolval($tbp['has_discounted_price']) ? $tbp['discounted_price'] : null,
                ]);
            }
        }

        return back()->with('success', 'Tariffe aggiornate');
    }
}
