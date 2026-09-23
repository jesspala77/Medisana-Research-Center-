<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommandCenterController extends Controller
{
    public function index(): View|RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->isPlatformAdmin()) {
            abort_unless($user->organization_key, 403);

            return redirect()->route('programs.show', $user->organization_key);
        }

        $scorecards = [
            [
                'label' => 'Enterprise Revenue',
                'value' => '$1.27M',
                'change' => '17.6%',
                'status' => 'positive',
                'detail' => 'vs last 30 days',
                'icon' => '$',
                'tone' => 'green',
            ],
            [
                'label' => 'Open Opportunities',
                'value' => '247',
                'change' => '18.6%',
                'status' => 'positive',
                'detail' => 'vs last 30 days',
                'icon' => '◎',
                'tone' => 'purple',
            ],
            [
                'label' => 'Open Projects',
                'value' => '32',
                'change' => '8.3%',
                'status' => 'positive',
                'detail' => 'vs last 30 days',
                'icon' => '□',
                'tone' => 'blue',
            ],
            [
                'label' => 'Tasks Due Today',
                'value' => '18',
                'change' => '5 overdue',
                'status' => 'warning',
                'detail' => 'Due today or overdue',
                'icon' => '✓',
                'tone' => 'orange',
            ],
            [
                'label' => 'Companies',
                'value' => '5',
                'change' => '3 divisions',
                'status' => 'positive',
                'detail' => 'Active client companies',
                'icon' => '▦',
                'tone' => 'teal',
            ],
            [
                'label' => 'AI Alerts',
                'value' => '6',
                'change' => '3 critical',
                'status' => 'danger',
                'detail' => '3 warnings',
                'icon' => '!',
                'tone' => 'red',
            ],
        ];

        $quickActions = collect([
            ['label' => 'New Lead', 'route' => route('bond-agency.leads.create'), 'type' => 'primary', 'module' => 'bond-agency'],
            ['label' => 'New Study', 'route' => route('clinical-recruitment.leads.create'), 'type' => 'success', 'module' => 'clinical-recruitment'],
            ['label' => 'New Project', 'route' => route('remodeling.projects.create'), 'type' => 'orange', 'module' => 'remodeling'],
            ['label' => 'Funding App', 'route' => route('capital-funding.leads.create'), 'type' => 'purple', 'module' => 'capital-funding'],
            ['label' => 'AI Assistant', 'route' => '#ai-search', 'type' => 'pink', 'module' => null],
        ])
            ->filter(fn (array $action): bool => $action['module'] === null || $user?->canAccessModule($action['module']))
            ->values()
            ->all();

        $executiveBriefing = [
            [
                'title' => '6 funding applications require follow-up',
                'detail' => 'Total value: $425,000',
                'severity' => 'green',
            ],
            [
                'title' => 'ARA has 3 overdue regulatory tasks',
                'detail' => 'Studies may be delayed',
                'severity' => 'teal',
            ],
            [
                'title' => 'Medisana CV packets need credential review',
                'detail' => 'Investigator and coordinator templates are ready',
                'severity' => 'blue',
            ],
            [
                'title' => 'K&G has 2 proposals awaiting approval',
                'detail' => 'Total value: $87,450',
                'severity' => 'orange',
            ],
            [
                'title' => 'Southernmost Surety has 5 agencies needing outreach',
                'detail' => 'Pipeline impact: High',
                'severity' => 'pink',
            ],
        ];

        $snapshot = [
            ['label' => 'Est. Revenue This Week', 'value' => '$162,400', 'change' => '12.4%'],
            ['label' => 'Revenue This Month', 'value' => '$1,268,000', 'change' => '17.6%'],
            ['label' => 'New Leads (30 Days)', 'value' => '86', 'change' => '22.1%'],
            ['label' => 'Conversion Rate', 'value' => '18.6%', 'change' => '3.4%'],
        ];

        $companies = [
            [
                'name' => 'ARA Professionals',
                'division' => 'Healthcare Division',
                'status' => 'Healthy',
                'revenue' => '$312K',
                'revenue_delta' => '16.3%',
                'tasks' => '4',
                'task_status' => 'Due today',
                'pipeline' => '18',
                'pipeline_status' => 'Active',
                'health' => 85,
                'program' => 'ara-professionals',
                'modules' => ['clinical-recruitment'],
                'avatar' => 'ARA',
            ],
            [
                'name' => 'Medisana Research Center',
                'division' => 'Healthcare Division',
                'status' => 'Healthy',
                'revenue' => '$184K',
                'revenue_delta' => '11.8%',
                'tasks' => 'CVs',
                'task_status' => 'Credential Review',
                'pipeline' => '12',
                'pipeline_status' => 'Study Staff',
                'health' => 81,
                'program' => 'medisana-research-center',
                'modules' => ['clinical-recruitment'],
                'avatar' => 'MRC',
            ],
            [
                'name' => 'J Funding Capital',
                'division' => 'Finance Division',
                'status' => 'Attention',
                'revenue' => '$425K',
                'revenue_delta' => '12.5%',
                'tasks' => '12',
                'task_status' => 'New',
                'pipeline' => '8',
                'pipeline_status' => 'Pending Docs',
                'health' => 62,
                'program' => 'j-funding-capital',
                'modules' => ['capital-funding'],
                'avatar' => 'JF',
            ],
            [
                'name' => 'Southernmost Surety',
                'division' => 'Finance Division',
                'status' => 'Attention',
                'revenue' => '$98K',
                'revenue_delta' => '5.2%',
                'tasks' => 'Agencies',
                'task_status' => 'Need Outreach',
                'pipeline' => '23',
                'pipeline_status' => 'Active',
                'health' => 58,
                'program' => 'southernmost-surety',
                'modules' => ['bond-agency'],
                'avatar' => 'SS',
            ],
            [
                'name' => 'K & G Art Design',
                'division' => 'Construction Division',
                'status' => 'Warning',
                'revenue' => '$354K',
                'revenue_delta' => '9.1%',
                'tasks' => '14 Quotes',
                'task_status' => 'CNC cutting services',
                'pipeline' => '5 Production',
                'pipeline_status' => '3 Installations',
                'health' => 64,
                'program' => 'k-and-g-art-design',
                'modules' => ['remodeling', 'cnc-quote'],
                'avatar' => 'K&G',
            ],
        ];

        $recentActivity = [
            [
                'title' => 'CV template prepared for Medisana site credentialing',
                'context' => 'Medisana Research Center - Clinical Staff Documentation',
                'time' => '1m ago',
                'tone' => 'blue',
            ],
            [
                'title' => 'Amanda Johnson completed Visit 8',
                'context' => 'ARA Professionals - Study AR-2024-008',
                'time' => '2m ago',
                'tone' => 'pink',
            ],
            [
                'title' => 'Janell Sherman uploaded ClinicalTrials.gov update',
                'context' => 'ARA Professionals - CTA-009 Revision',
                'time' => '15m ago',
                'tone' => 'purple',
            ],
            [
                'title' => 'Proposal #KAG-2024-118 approved by client',
                'context' => 'K & G Art Design - Kitchen Remodel',
                'time' => '1h ago',
                'tone' => 'red',
            ],
            [
                'title' => 'Invoice #INV-2024-457 paid by Greenway LLC',
                'context' => 'J Funding Capital - Invoice Paid',
                'time' => '2h ago',
                'tone' => 'yellow',
            ],
            [
                'title' => 'Dr. Stevens added to Site #FL-102',
                'context' => 'ARA Professionals - New Investigator',
                'time' => '3h ago',
                'tone' => 'blue',
            ],
        ];

        $todaySchedule = [
            ['time' => '9:00 AM', 'title' => 'Sponsor Meeting', 'context' => 'ARA Professionals', 'tone' => 'purple'],
            ['time' => '10:00 AM', 'title' => 'Medisana CV Credential Review', 'context' => 'Medisana Research Center', 'tone' => 'blue'],
            ['time' => '11:00 AM', 'title' => 'Construction Estimate Review', 'context' => 'K & G Art Design', 'tone' => 'purple'],
            ['time' => '1:00 PM', 'title' => 'Funding Strategy Call', 'context' => 'J Funding Capital', 'tone' => 'orange'],
            ['time' => '2:30 PM', 'title' => 'CNC Production Review', 'context' => 'K & G Art Design', 'tone' => 'teal'],
            ['time' => '4:00 PM', 'title' => 'Executive Review', 'context' => 'Global Synergia Group', 'tone' => 'purple'],
        ];

        $revenueBreakdown = [
            ['label' => 'Healthcare', 'value' => '$496K (39%)', 'tone' => 'blue'],
            ['label' => 'Finance & Surety', 'value' => '$327K (26%)', 'tone' => 'teal'],
            ['label' => 'Construction', 'value' => '$354K (28%)', 'tone' => 'yellow'],
            ['label' => 'Technology & AI', 'value' => '$91K (7%)', 'tone' => 'purple'],
        ];

        $pipelineStages = [
            ['label' => 'New Leads', 'value' => 86, 'change' => '12.4%', 'width' => 100],
            ['label' => 'Qualification', 'value' => 64, 'change' => '8.7%', 'width' => 82],
            ['label' => 'Proposal', 'value' => 38, 'change' => '15.6%', 'width' => 64],
            ['label' => 'Negotiation', 'value' => 24, 'change' => '9.1%', 'width' => 48],
            ['label' => 'Closed Won', 'value' => 15, 'change' => '21.3%', 'width' => 32],
        ];

        return view('dashboard', compact(
            'scorecards',
            'quickActions',
            'executiveBriefing',
            'snapshot',
            'companies',
            'recentActivity',
            'todaySchedule',
            'revenueBreakdown',
            'pipelineStages'
        ));
    }
}
