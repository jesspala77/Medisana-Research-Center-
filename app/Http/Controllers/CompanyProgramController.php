<?php

namespace App\Http\Controllers;

use App\Models\AiOutreachMessage;
use App\Models\Contact;
use App\Models\RemodelingProject;
use App\Services\CompanyProgramService;
use App\Services\LeadModuleService;
use App\Services\RemodelingDashboardService;
use Illuminate\Http\Request;

class CompanyProgramController extends Controller
{
    public function show(
        Request $request,
        string $program,
        CompanyProgramService $programs,
        LeadModuleService $leadModules,
        RemodelingDashboardService $remodelingDashboard
    ) {
        $programConfig = $programs->get($program);
        abort_if(! $programConfig, 404);

        $user = $request->user();
        abort_unless($user->canAccessOrganization($program), 403);

        $allowedModules = collect($programConfig['modules'])
            ->filter(fn (string $module) => $user->canAccessModule($module))
            ->values()
            ->all();

        if (($programConfig['admin_only'] ?? false) && ! $user->isPlatformAdmin()) {
            abort(403);
        }

        abort_if(! ($programConfig['admin_only'] ?? false) && $allowedModules === [], 403);

        return view('programs.show', [
            'program' => $programConfig,
            'moduleCards' => $this->moduleCards($allowedModules, $leadModules, $remodelingDashboard, $user->isPlatformAdmin() ? null : $user->organization_key),
            'actions' => $this->actions($allowedModules, (bool) ($programConfig['admin_only'] ?? false)),
            'agents' => $this->agents($allowedModules, $leadModules, $user->isPlatformAdmin() ? null : $user->organization_key),
        ]);
    }

    private function moduleCards(
        array $modules,
        LeadModuleService $leadModules,
        RemodelingDashboardService $remodelingDashboard,
        ?string $organizationKey
    ): array {
        $cards = [];

        foreach ($modules as $moduleKey) {
            if ($moduleKey === 'remodeling') {
                $summary = $remodelingDashboard->summary();
                $cards[] = [
                    'title' => 'Construction & Remodeling KPIs',
                    'module' => 'remodeling',
                    'dashboard_route' => route('remodeling.dashboard'),
                    'metrics' => [
                        ['label' => 'Projects', 'value' => number_format($summary['projects_total'])],
                        ['label' => 'Active', 'value' => number_format($summary['active_projects'])],
                        ['label' => 'New Leads', 'value' => number_format($summary['new_leads'])],
                        ['label' => 'Site Visits', 'value' => number_format($summary['site_visits_scheduled'])],
                        ['label' => 'Draft Estimates', 'value' => number_format($summary['estimates_draft'])],
                        ['label' => 'Sent Estimate Value', 'value' => '$'.number_format($summary['estimates_sent_value'], 2)],
                    ],
                ];

                continue;
            }

            $module = $leadModules->get($moduleKey);
            $summary = $leadModules->summary($module, $organizationKey);
            $metrics = [
                ['label' => 'Total Leads', 'value' => number_format($summary['total'])],
                ['label' => 'Open', 'value' => number_format($summary['open'])],
                ['label' => 'New Intake', 'value' => number_format($summary['new'])],
                ['label' => 'Converted', 'value' => number_format($summary['won'])],
                ['label' => $module['value_label'], 'value' => '$'.number_format($summary['pipeline_value'], 2)],
                ['label' => 'Average Score', 'value' => number_format($summary['average_score'])],
            ];

            if ($moduleKey === 'bond-agency') {
                $metrics[] = ['label' => 'Draft Outreach', 'value' => number_format(AiOutreachMessage::where('status', 'draft')->count())];
                $metrics[] = ['label' => 'Approved To Send', 'value' => number_format(AiOutreachMessage::where('status', 'approved')->count())];
            }

            $cards[] = [
                'title' => $module['title'].' KPIs',
                'module' => $moduleKey,
                'dashboard_route' => route($module['route_name'].'.dashboard'),
                'metrics' => $metrics,
            ];
        }

        return $cards;
    }

    private function actions(array $modules, bool $adminOnly): array
    {
        $actions = [];

        foreach ($modules as $moduleKey) {
            if ($moduleKey === 'remodeling') {
                $actions[] = ['label' => 'Remodeling Dashboard', 'href' => route('remodeling.dashboard')];
                $actions[] = ['label' => 'Projects', 'href' => route('remodeling.projects.index')];
                $actions[] = ['label' => 'Create Project', 'href' => route('remodeling.projects.create')];

                continue;
            }

            $routePrefix = $moduleKey;
            $actions[] = ['label' => str($moduleKey)->headline().' Dashboard', 'href' => route($routePrefix.'.dashboard')];
            $actions[] = ['label' => 'Leads', 'href' => route($routePrefix.'.leads.index')];
            $actions[] = ['label' => 'Create Lead', 'href' => route($routePrefix.'.leads.create')];

            if ($moduleKey === 'bond-agency') {
                $actions[] = ['label' => 'Import Agencies', 'href' => route('bond-agency.import')];
                $actions[] = ['label' => 'Outreach Queue', 'href' => route('bond-agency.outreach.index')];
            }

            if ($moduleKey === 'clinical-recruitment') {
                $actions[] = ['label' => 'Regulatory Services', 'href' => route('clinical-regulatory.dashboard')];
            }
        }

        if ($adminOnly) {
            $actions[] = ['label' => 'Enterprise Dashboard', 'href' => route('dashboard')];
            $actions[] = ['label' => 'User Access', 'href' => route('admin.user-access.index')];
            $actions[] = ['label' => 'Command Queue', 'href' => route('command-queue.index')];
        }

        return $actions;
    }

    private function agents(array $modules, LeadModuleService $leadModules, ?string $organizationKey): array
    {
        $agents = collect();

        foreach ($modules as $moduleKey) {
            if ($moduleKey === 'remodeling') {
                $agents = $agents->merge(
                    RemodelingProject::with('contact')
                        ->whereNotNull('contact_id')
                        ->latest()
                        ->limit(8)
                        ->get()
                        ->map(fn (RemodelingProject $project) => [
                            'name' => trim(($project->contact?->first_name ?? '').' '.($project->contact?->last_name ?? '')) ?: 'Unnamed contact',
                            'email' => $project->contact?->email,
                            'phone' => $project->contact?->phone,
                            'context' => $project->project_name,
                        ])
                );

                continue;
            }

            $module = $leadModules->get($moduleKey);
            $agents = $agents->merge(
                Contact::whereHas('leads', fn ($query) => $query
                    ->when($organizationKey, fn ($query) => $query->where('organization_key', $organizationKey))
                    ->whereHas('industry', fn ($query) => $query->where('slug', $module['industry']['slug'])))
                    ->with('company')
                    ->latest()
                    ->limit(8)
                    ->get()
                    ->map(fn (Contact $contact) => [
                        'name' => trim(($contact->first_name ?? '').' '.($contact->last_name ?? '')) ?: $contact->first_name ?: 'Unnamed contact',
                        'email' => $contact->email,
                        'phone' => $contact->phone,
                        'context' => $contact->company?->name ?: $module['short_title'],
                    ])
            );
        }

        return $agents->unique(fn (array $agent) => ($agent['email'] ?: $agent['phone'] ?: $agent['name']).$agent['context'])
            ->take(12)
            ->values()
            ->all();
    }
}
