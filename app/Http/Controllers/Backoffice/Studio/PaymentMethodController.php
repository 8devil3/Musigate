<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentMethodController extends Controller
{
    public function edit(): Response
    {
        $all_payments = PaymentMethod::get()->mapWithKeys(function($payment){
            return [
                $payment->id => [
                    'name' => $payment->name,
                    'img_name' => $payment->img_name,
                ]
            ];
        });

        $payments = auth()->user()->studio->payment_methods->pluck('id');

        return Inertia::render('Backoffice/Studio/PaymentMethods', compact('payments', 'all_payments'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'payments' => 'nullable|exists:payment_methods,id',
        ]);

        auth()->user()->studio->payment_methods()->sync($request->payments);

        return redirect()->back();
    }
}
