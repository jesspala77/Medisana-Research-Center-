<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MedisanaResearchCenterController extends Controller
{
    public function __invoke(?string $page = null): View
    {
        $allowedPages = ['patients', 'sponsors', 'capabilities', 'readiness', 'careers', 'contact'];

        abort_unless($page === null || in_array($page, $allowedPages, true), 404);

        return view('medisana.research-center', [
            'page' => $page ?? 'home',
        ]);
    }
}
