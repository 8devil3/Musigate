<?php

namespace App\Http\Controllers\Auth\RegisterStudio;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function choose_plan(): Response
    {
        return Inertia::render('Auth/Studio/Subscriptions');
    }
}
