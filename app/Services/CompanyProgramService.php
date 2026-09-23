<?php

namespace App\Services;

class CompanyProgramService
{
    public function all(): array
    {
        return [
            'j-funding-capital' => [
                'name' => 'J Funding Capital',
                'division' => 'Finance, Surety & Risk Solutions',
                'status' => 'Active',
                'modules' => ['capital-funding'],
                'summary' => 'Funding applications, underwriting readiness, offers, funded deals, and client follow-up.',
                'client_focus' => 'Business owners seeking working capital and funding options.',
            ],
            'southernmost-surety' => [
                'name' => 'Southernmost Surety',
                'division' => 'Finance, Surety & Risk Solutions',
                'status' => 'Active',
                'modules' => ['bond-agency'],
                'summary' => 'Bail bond agency development, Palmetto outreach, coverage gaps, policy pipeline, and agent follow-up.',
                'client_focus' => 'Bail bond agencies, producers, and referral partners.',
            ],
            'ara-professionals' => [
                'name' => 'ARA Professionals',
                'division' => 'Healthcare & Clinical Research',
                'status' => 'Active',
                'modules' => ['clinical-recruitment'],
                'summary' => 'Clinical candidate intake, prescreening, protocol fit, site coordination, and enrollment workflow.',
                'client_focus' => 'Patients, research candidates, physicians, and study partners.',
            ],
            'ara-wellness-center' => [
                'name' => 'ARA Wellness Center',
                'division' => 'Healthcare & Clinical Research',
                'status' => 'Active',
                'modules' => ['clinical-recruitment'],
                'summary' => 'Wellness service interest, patient engagement, healthcare follow-up, and referral workflow.',
                'client_focus' => 'Wellness patients and healthcare service clients.',
            ],
            'medisana-research-center' => [
                'name' => 'Medisana Research Center',
                'division' => 'Healthcare & Clinical Research',
                'status' => 'Active',
                'modules' => ['clinical-recruitment'],
                'summary' => 'Study staff CV readiness, clinical candidate intake, protocol fit, prescreening, site coordination, and enrollment workflow.',
                'client_focus' => 'Investigators, coordinators, research candidates, referring providers, sponsors, and study partners.',
            ],
            'k-and-g-art-design' => [
                'name' => 'K & G Art Design',
                'division' => 'Construction, Design & Manufacturing',
                'status' => 'Active',
                'modules' => ['remodeling', 'cnc-quote'],
                'summary' => 'Remodeling projects, site visits, estimates, proposals, contracts, CNC cutting services, RFQ intake, and production handoff visibility.',
                'client_focus' => 'Residential and commercial remodeling clients plus K & G production clients submitting CNC cutting and machining requests.',
            ],
            'synnexus' => [
                'name' => 'SynNexus',
                'division' => 'Enterprise Technology & AI',
                'status' => 'Active',
                'modules' => [],
                'admin_only' => true,
                'summary' => 'Enterprise operating system administration, automation, AI workflow design, and platform oversight.',
                'client_focus' => 'Internal platform administration and executive operations.',
            ],
        ];
    }

    public function get(string $key): ?array
    {
        $program = $this->all()[$key] ?? null;

        return $program ? $program + ['key' => $key] : null;
    }
}
