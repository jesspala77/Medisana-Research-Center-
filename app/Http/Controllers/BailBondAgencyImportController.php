<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Services\AiOutreachDraftService;
use App\Services\LeadModuleService;
use Illuminate\Http\Request;

class BailBondAgencyImportController extends Controller
{
    public function create()
    {
        return view('lead-modules.bond-agency-import');
    }

    public function store(
        Request $request,
        LeadModuleService $moduleService,
        AiOutreachDraftService $draftService
    ) {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $moduleConfig = $moduleService->get('bond-agency');
        $industry = $moduleService->ensureIndustry($moduleConfig);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');

        $headers = fgetcsv($file);
        $headers = array_map(fn ($header) => $this->normalizeHeader($header), $headers ?: []);

        $imported = 0;

        while (($row = fgetcsv($file)) !== false) {
            $row = array_slice(array_pad($row, count($headers), null), 0, count($headers));
            $data = $this->normalizeImportRow(array_combine($headers, $row));

            if (empty($data['agency_name'])) {
                continue;
            }

            $company = Company::firstOrCreate(
                [
                    'industry_id' => $industry->id,
                    'name' => $data['agency_name'],
                ],
                [
                    'website' => $data['website'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                    'address' => $data['address'] ?? null,
                    'city' => $data['city'] ?? null,
                    'state' => $data['state'] ?? null,
                    'zip' => $data['zip'] ?? null,
                    'metadata' => [
                        'county' => $data['county'] ?? null,
                        'source' => $data['_source'],
                        'license_number' => $data['_license_number'],
                        'npn' => $data['_npn'],
                        'license_status' => $data['_license_status'],
                    ],
                ]
            );

            if (! empty($data['email'])) {
                $contact = Contact::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'email' => $data['email'],
                    ],
                    [
                        'first_name' => $data['contact_name'] ?? null,
                        'title' => 'Agency Contact',
                        'phone' => $data['phone'] ?? null,
                        'preferred_contact_method' => 'email',
                        'metadata' => [
                            'source' => $data['_source'],
                            'license_number' => $data['_license_number'],
                            'npn' => $data['_npn'],
                        ],
                    ]
                );
            } else {
                $contact = Contact::create([
                    'company_id' => $company->id,
                    'first_name' => $data['contact_name'] ?? null,
                    'title' => 'Agency Contact',
                    'phone' => $data['phone'] ?? null,
                    'email' => null,
                    'preferred_contact_method' => 'phone',
                    'metadata' => [
                        'source' => $data['_source'],
                        'license_number' => $data['_license_number'],
                        'npn' => $data['_npn'],
                    ],
                ]);
            }

            $lead = Lead::create([
                'industry_id' => $industry->id,
                'company_id' => $company->id,
                'contact_id' => $contact->id,
                'title' => $data['agency_name'].' - Palmetto Opportunity',
                'status' => $moduleConfig['default_status'],
                'stage' => 'intake',
                'priority' => 'high',
                'estimated_value' => 0,
                'lead_score' => 65,
                'quality_score' => 60,
                'urgency_score' => 70,
                'fit_score' => 75,
                'completeness_score' => 50,
                'sla_status' => 'healthy',
                'surety_pipeline' => 'New Agency Lead',
                'palmetto_interest_level' => 'Medium',
                'next_follow_up_at' => now()->addDay(),
                'next_best_action' => 'Review agency and approve AI outreach draft',
                'metadata' => [
                    'module' => 'bond-agency',
                    'lead_source' => $data['_source'],
                    'city' => $data['city'] ?? null,
                    'state' => $data['state'] ?? null,
                    'county' => $data['county'] ?? null,
                    'website' => $data['website'] ?? null,
                    'license_number' => $data['_license_number'],
                    'npn' => $data['_npn'],
                    'license_type' => $data['_license_type'],
                    'license_status' => $data['_license_status'],
                    'license_line' => $data['_license_line'],
                    'license_effective_date' => $data['_license_effective_date'],
                    'license_expiration_date' => $data['_license_expiration_date'],
                    'resident_status' => $data['_resident_status'],
                    'domicile_state' => $data['_domicile_state'],
                ],
            ]);

            $profileModel = $moduleConfig['profile_model'];

            $profileModel::create([
                'lead_id' => $lead->id,
                'county' => $data['county'] ?? null,
                'agency_type' => 'Bail bond agency',
                'current_products' => $data['current_surety'] ?? null,
                'coverage_gap' => $data['coverage_gap'] ?? 'Potential opportunity for Palmetto coverage, lower rate, and electronic powers.',
                'policy_status' => 'Prospect',
                'referral_partner' => 'Southernmost Surety',
                'current_surety' => $data['current_surety'] ?? null,
                'palmetto_eligible' => true,
                'epower_interest' => true,
                'southernmost_status' => 'Prospecting',
                'palmetto_stage' => 'New Agency Lead',
            ]);

            $draftService->createPalmettoDraft($lead);

            $imported++;
        }

        fclose($file);

        return back()->with('success', "{$imported} bond agencies imported and AI outreach drafts created.");
    }

    private function normalizeImportRow(array $row): array
    {
        $source = $this->hasAnyValue($row, [
            'licenseno',
            'licensenumber',
            'nationalproducernumbernpn',
            'npn',
            'licenseclass',
            'licenseclassdesc',
            'licensetype',
            'licenselineofauthority',
            'lineofauthority',
            'loa',
        ]) ? 'difi_sbs_import' : 'csv_import';

        return [
            'agency_name' => $this->firstValue($row, [
                'agencyname',
                'businessentityname',
                'businessname',
                'entityname',
                'organizationname',
                'companyname',
                'licenseename',
                'licensedname',
                'individualbondsmanorrunnername',
                'individualname',
                'producername',
                'fullname',
                'name',
            ]) ?: $this->fullName($row),
            'contact_name' => $this->firstValue($row, [
                'contactname',
                'individualbondsmanorrunnername',
                'individualname',
                'producername',
                'designatedresponsiblelicensedproducer',
                'drlp',
                'licenseename',
                'fullname',
            ]) ?: $this->fullName($row),
            'email' => $this->firstValue($row, [
                'email',
                'email1',
                'emailaddress',
                'businessemail',
                'businessmail',
                'contactemail',
            ]),
            'phone' => $this->firstValue($row, [
                'phone',
                'phone1',
                'phonenumber',
                'businessphone',
                'businessphonenumber',
                'contactphone',
            ]),
            'address' => $this->addressValue($row),
            'city' => $this->firstValue($row, [
                'city',
                'buscity',
                'businesscity',
                'mailingcity',
                'mlgcity',
            ]),
            'state' => $this->stateValue($this->firstValue($row, [
                'state',
                'busstate',
                'businessstate',
                'mailingstate',
                'mlgstate',
            ]) ?: ($source === 'difi_sbs_import' ? 'AZ' : null)),
            'zip' => $this->firstValue($row, [
                'zip',
                'zipcode',
                'postalcode',
                'buszip',
                'businesszip',
                'businesszipcode',
                'mailingzip',
                'mailingzipcode',
                'mlgzip',
            ]),
            'county' => $this->firstValue($row, [
                'county',
                'businesscounty',
                'mailingcounty',
            ]),
            'website' => $this->firstValue($row, [
                'website',
                'websitedomain',
                'businesswebsite',
                'url',
            ]),
            'current_surety' => $this->firstValue($row, [
                'currentsurety',
                'surety',
                'appointingcompany',
                'appointmentcompany',
                'insurer',
            ]),
            'coverage_gap' => $this->firstValue($row, [
                'coveragegap',
            ]) ?: 'Potential opportunity for Palmetto coverage, lower rate, and electronic powers.',
            '_source' => $source,
            '_license_number' => $this->firstValue($row, ['licenseno', 'licensenumber', 'licenseid']),
            '_npn' => $this->firstValue($row, ['npn', 'nationalproducernumbernpn', 'nationalproducernumber']),
            '_license_type' => $this->firstValue($row, ['licensetype', 'licenseclass', 'licenseclassdesc', 'licenseclassandstatus']),
            '_license_status' => $this->firstValue($row, ['licensestatus', 'status', 'licenseclassandstatus']),
            '_license_line' => $this->firstValue($row, ['licenselineofauthority', 'lineofauthority', 'loa']),
            '_license_effective_date' => $this->firstValue($row, ['effectivedate', 'licenseeffectivedate', 'firstactivedate', 'firstactivedateoflicense']),
            '_license_expiration_date' => $this->firstValue($row, ['expirationdate', 'licenseexpirationdate']),
            '_resident_status' => $this->firstValue($row, ['residentstatus', 'residencestatus', 'isresident']),
            '_domicile_state' => $this->stateValue($this->firstValue($row, ['domicilestate', 'residentstate'])),
        ];
    }

    private function normalizeHeader(?string $header): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $header)));
    }

    private function firstValue(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = trim((string) ($row[$key] ?? ''));

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function hasAnyValue(array $row, array $keys): bool
    {
        return $this->firstValue($row, $keys) !== null;
    }

    private function addressValue(array $row): ?string
    {
        $parts = array_filter([
            $this->firstValue($row, ['busaddress1']),
            $this->firstValue($row, ['busaddress2']),
            $this->firstValue($row, ['busaddress3']),
            $this->firstValue($row, ['busaddress4']),
            $this->firstValue($row, ['busaddress5']),
            $this->firstValue($row, ['busaddress6']),
            $this->firstValue($row, ['businessaddressline1', 'addressline1', 'mailingaddressline1']),
            $this->firstValue($row, ['businessaddressline2', 'addressline2', 'mailingaddressline2']),
        ]);

        if ($parts) {
            return implode(', ', $parts);
        }

        $address = $this->firstValue($row, [
            'address',
            'businessaddress',
            'fullbusinessaddress',
            'mailingaddress',
            'fullmailingaddress',
            'streetaddress',
            'mlgaddress1',
        ]);

        if ($address) {
            return $address;
        }

        return null;
    }

    private function fullName(array $row): ?string
    {
        $parts = array_filter([
            $this->firstValue($row, ['firstname']),
            $this->firstValue($row, ['middlename']),
            $this->firstValue($row, ['lastname']),
            $this->firstValue($row, ['suffix']),
        ]);

        return $parts ? implode(' ', $parts) : null;
    }

    private function stateValue(?string $state): ?string
    {
        if (! $state) {
            return null;
        }

        return strtolower($state) === 'arizona' ? 'AZ' : $state;
    }
}
