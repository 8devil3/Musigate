<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LegalTextController extends Controller
{
    public function privacy(): Response
    {
        return Inertia::render('Frontoffice/Legal/Privacy');
    }

    public function tos(): Response
    {
        return Inertia::render('Frontoffice/Legal/Tos');

    }

    public function cookie(): Response
    {
        return Inertia::render('Frontoffice/Legal/Cookie');

    }
}
