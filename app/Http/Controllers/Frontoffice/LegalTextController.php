<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LegalTextController extends Controller
{
    public function privacy(): Response
    {
        $privacy = \Str::markdown(file_get_contents(resource_path('views/legal-text/privacy.md')));

        return Inertia::render('Frontoffice/LegalText', compact('privacy'));
    }

    public function tos(): Response
    {
        $tos = \Str::markdown(file_get_contents((resource_path('views/legal-text/tos.md'))));

        return Inertia::render('Frontoffice/LegalText', compact('tos'));
    }

    public function cookie(): Response
    {
        $cookie = \Str::markdown(file_get_contents(resource_path('views/legal-text/cookie.md')));

        return Inertia::render('Frontoffice/LegalText', compact('cookie'));
    }
}
