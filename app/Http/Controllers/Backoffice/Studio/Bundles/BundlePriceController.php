<?php

namespace App\Http\Controllers\Backoffice\Studio\Bundles;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Picklists;
use App\Models\Bundle\Bundle;
use App\Models\Studio\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BundlePriceController extends Controller
{
    public function edit(Bundle $bundle): Response
    {
        $price_types = Picklists::BUNDLE_PRICE_TYPES;
        $studio = auth()->user()->studio;

        $open_weekdays = $studio->availability()->whereNot('open_type', 'close')->get()
            ->map(function($av): array {
                return [
                    'weekday' => $av->weekday,
                    'label' => Picklists::WEEKDAYS[$av->weekday],
                    'availability_id' => $av->id,
                ];
            });

        $timebands = $studio->timebands;
        $timeband_prices = $bundle->timeband_prices;
        $weekday_prices = $bundle->weekday_prices;

        //riempio l'array delle tariffe a giornata con valori nulli per i giorni (open_weekdays) senza tariffe
        foreach ($open_weekdays as $owd) {
            if(!in_array($owd['availability_id'], $bundle->weekday_prices()->pluck('availability_id')->toArray())){
                $weekday_prices[] = [
                    'id' => null,
                    'availability_id' => $owd['availability_id'],
                    'price' => null,
                    'has_discounted_price' => false,
                    'discounted_price' => null,
                ];
            }
        }

        //se non ci sono tariffe a fasce orarie creo un array con valori nulli
        if($timeband_prices->isEmpty()){
            foreach ($timebands as $tb) {
                $timeband_prices[] = [
                    'id' => null,
                    'timeband_id' => $tb['id'],
                    'price' => null,
                    'has_discounted_price' => false,
                    'discounted_price' => null,
                ];
            }
        }

        return Inertia::render('Backoffice/Studio/Bundles/Prices', compact('bundle', 'timeband_prices', 'weekday_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $request->validate([
            'price_type' => ['required', 'string', 'in:' . implode(',', array_keys(Picklists::BUNDLE_PRICE_TYPES))],
        ]);

        $price_type = $request->price_type;

        switch ($price_type) {
            case 'no_price':
                $bundle->update([
                    'price_type' => $price_type,
                    'fixed_price' => null,
                    'has_discounted_fixed_price' => false,
                    'discounted_fixed_price' => null,
                    'is_bookable' => false,
                ]);

                $bundle->timeband_prices()->delete();
                $bundle->weekday_prices()->delete();
            break;

            case 'fixed_price':
                $request->validate([
                    'fixed_price' => ['required', 'integer', 'min:2'],
                    'has_discounted_fixed_price' => ['boolean'],
                    'discounted_fixed_price' => ['nullable', 'required_if:has_discounted_fixed_price,accepted', 'integer', 'min:1', 'lt:fixed_price'],
                ]);

                $has_discounted_fixed_price = boolval($request->has_discounted_fixed_price);

                $bundle->update([
                    'price_type' => $price_type,
                    'fixed_price' => $request->fixed_price,
                    'has_discounted_fixed_price' => $has_discounted_fixed_price,
                    'discounted_fixed_price' => $has_discounted_fixed_price ? $request->discounted_fixed_price : null,
                ]);

                $bundle->timeband_prices()->delete();
                $bundle->weekday_prices()->delete();
            break;

            case 'timebands_price':
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

                $bundle->weekday_prices()->delete();

                foreach ($request->timeband_prices as $tbp) {
                    $has_discounted_price = boolval($tbp['has_discounted_price']);
    
                    $bundle->timeband_prices()->updateOrCreate([
                        'id' => $tbp['id'],
                    ], [
                        'timeband_id' => $tbp['timeband_id'],
                        'price' => $tbp['price'],
                        'has_discounted_price' => $has_discounted_price,
                        'discounted_price' => $has_discounted_price ? $tbp['discounted_price'] : null,
                    ]);
                }
            break;

            case 'weekdays_price':
                $request->validate([
                    'weekday_prices' => ['required', 'array', 'min:1', 'max:' . count(Picklists::WEEKDAYS)],
                    'weekday_prices.*.availability_id' => ['required', 'integer', 'exists:availabilities,id'],
                    'weekday_prices.*.price' => ['required', 'integer', 'min:2'],
                    'weekday_prices.*.has_discounted_price' => ['boolean'],
                    'weekday_prices.*.discounted_price' => ['nullable', 'required_if:has_discounted_price,accepted', 'integer', 'min:1', 'lt:weekday_prices.*.price'],
                ]);

                $bundle->update([
                    'price_type' => $price_type,
                    'fixed_price' => null,
                    'has_discounted_fixed_price' => false,
                    'discounted_fixed_price' => null,
                ]);

                $bundle->timeband_prices()->delete();

                foreach ($request->weekday_prices as $wdp) {
                    $has_discounted_price = boolval($wdp['has_discounted_price']);
    
                    $bundle->weekday_prices()->updateOrCreate([
                        'id' => $wdp['id'],
                    ],[
                        'availability_id' => $wdp['availability_id'],
                        'weekday' => Availability::findOrFail($wdp['availability_id'])->weekday,
                        'price' => $wdp['price'],
                        'has_discounted_price' => $has_discounted_price,
                        'discounted_price' => $has_discounted_price ? $wdp['discounted_price'] : null,
                    ]);
                }
            break;
        }

        return back()->with('success', 'Tariffe aggiornate');
    }
}
