<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MedisanaResearchCenterController extends Controller
{
    public function spanishPatients(): View
    {
        return view('medisana.research-center', [
            'page' => 'patients',
            'locale' => 'es',
        ]);
    }

    public function __invoke(?string $page = null): View
    {
        $allowedPages = [
            'overview',
            'patients',
            'sponsors',
            'capabilities',
            'readiness',
            'careers',
            'contact',
        ];

        abort_unless(
            $page === null || in_array($page, $allowedPages, true),
            404
        );

        $resolvedPage = $page === 'overview'
            ? 'home'
            : ($page ?? 'home');

        return view('medisana.research-center', [
            'page' => $resolvedPage,
        ]);
    }
}
