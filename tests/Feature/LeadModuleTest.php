<?php

namespace Tests\Feature;

use App\Models\AiOutreachMessage;
use App\Models\Contact;
use App\Models\Industry;
use App\Models\Lead;
use App\Models\RegulatoryServiceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LeadModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->platformAdmin()->create());
    }

    public function test_lead_module_dashboards_render(): void
    {
        foreach (['/capital-funding', '/bond-agency', '/clinical-recruitment', '/clinical-regulatory', '/cnc-quote'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_clinical_recruitment_dashboard_stays_candidate_focused(): void
    {
        $this->get('/clinical-recruitment')
            ->assertOk()
            ->assertSee('Clinical Trial Recruitment')
            ->assertSee('Create Candidate')
            ->assertSee('Patient candidate intake, prescreening, protocol fit, visit scheduling, and enrollment tracking.')
            ->assertDontSee('Clinical Research Regulatory Services')
            ->assertDontSee('Start Tracker Item')
            ->assertDontSee('portfolio/templates/cv-template.md');
    }

    public function test_clinical_regulatory_dashboard_renders_service_workspace(): void
    {
        $this->get('/clinical-regulatory')
            ->assertOk()
            ->assertSee('Regulatory Services')
            ->assertSee('Regulatory Binder / eReg Setup')
            ->assertSee('IRB Submission Support')
            ->assertSee('Protocol Deviation &amp; CAPA Support', false)
            ->assertSee('Create Regulatory Record')
            ->assertSee('Start Record')
            ->assertSee('Clinical Research Regulatory Services Menu')
            ->assertSee('portfolio/templates/clinical-research-regulatory-services.md')
            ->assertSee('CV Template')
            ->assertSee('portfolio/templates/cv-template.md')
            ->assertSee('Recruitment Dashboard');
    }

    public function test_clinical_regulatory_service_card_prefills_record_form(): void
    {
        $this->get('/clinical-regulatory/records/create?service_category=regulatory_binder')
            ->assertOk()
            ->assertSee('Regulatory Binder / eReg Setup')
            ->assertSee('regulatory_binder')
            ->assertSee('Set up binder structure, collect essential documents, confirm version control, and identify missing items.');
    }

    public function test_clinical_regulatory_record_can_be_created_in_separate_database_table(): void
    {
        $response = $this->post('/clinical-regulatory/records', [
            'client_name' => 'Medisana Research Center',
            'site_name' => 'Miami site',
            'service_category' => 'audit_readiness',
            'request_title' => 'Audit Readiness Review',
            'sponsor' => 'Northstar CRO',
            'protocol' => 'MRC-101',
            'primary_contact_name' => 'Avery Stone',
            'primary_contact_email' => 'avery@example.com',
            'regulatory_owner' => 'Jordan Regulatory',
            'status' => 'in_review',
            'priority' => 'urgent',
            'due_date' => '2026-09-30',
            'documents_available' => 'Signed delegation log and current consent version.',
            'missing_documents' => 'Updated GCP certificates.',
            'approval_path' => 'PI review, then sponsor approval.',
            'next_step' => 'Request missing certificates',
            'notes' => 'Review binder completeness, consent versions, training logs, and open findings.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('regulatory_service_records', [
            'client_name' => 'Medisana Research Center',
            'request_title' => 'Audit Readiness Review',
            'service_category' => 'audit_readiness',
            'status' => 'in_review',
            'priority' => 'urgent',
        ]);
        $this->assertDatabaseMissing('leads', [
            'title' => 'Audit Readiness Review',
        ]);
    }

    public function test_clinical_regulatory_record_status_can_be_updated(): void
    {
        $record = RegulatoryServiceRecord::create([
            'client_name' => 'Medisana Research Center',
            'service_category' => 'credentialing',
            'request_title' => 'CV and Credential Packet',
            'status' => 'not_started',
            'priority' => 'normal',
        ]);

        $this->post(route('clinical-regulatory.records.status', $record), [
            'status' => 'ready',
            'priority' => 'high',
            'due_date' => '2026-09-21',
            'current_blocker' => null,
            'missing_documents' => 'None',
            'next_step' => 'Send packet to sponsor',
            'notes' => 'CV Template completed and credential packet ready.',
        ])->assertRedirect();

        $this->assertDatabaseHas('regulatory_service_records', [
            'id' => $record->id,
            'status' => 'ready',
            'priority' => 'high',
            'next_step' => 'Send packet to sponsor',
        ]);
    }

    public function test_command_center_renders_executive_command_center(): void
    {
        AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_name' => 'Dana Rivera',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'draft',
        ]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Enterprise Command Center')
            ->assertSee('Search the enterprise')
            ->assertSee('AI Executive Briefing')
            ->assertSee('Company Health Overview')
            ->assertSee("Today's Schedule", false)
            ->assertSee('New Lead')
            ->assertSee('Funding App')
            ->assertSee('Southernmost Surety')
            ->assertSee('CNC cutting services')
            ->assertSee(route('programs.show', 'southernmost-surety'))
            ->assertSee(route('programs.show', 'k-and-g-art-design'))
            ->assertDontSee('Research Management Partners')
            ->assertDontSee('MRU Research')
            ->assertDontSee('CNC Operations')
            ->assertDontSee(route('programs.show', 'cnc-cutting-services'));
    }

    public function test_company_program_workspace_renders_kpis_actions_and_agents(): void
    {
        $this->createCommandQueueLead();

        $this->get(route('programs.show', 'southernmost-surety'))
            ->assertOk()
            ->assertSee('Southernmost Surety Program')
            ->assertSee('Bond Agency Insurance Leads KPIs')
            ->assertSee('Outreach Queue')
            ->assertSee('Program Directory')
            ->assertSee('Dana Rivera');
    }

    public function test_medisana_program_links_to_clinical_regulatory_services(): void
    {
        $this->get(route('programs.show', 'medisana-research-center'))
            ->assertOk()
            ->assertSee('Medisana Research Center Program')
            ->assertSee('Clinical Trial Recruitment KPIs')
            ->assertSee('Regulatory Services')
            ->assertSee(route('clinical-regulatory.dashboard'));
    }

    public function test_company_program_access_follows_module_access(): void
    {
        $this->actingAs(User::factory()->fundingClientUser()->create());

        $this->get(route('programs.show', 'j-funding-capital'))
            ->assertOk()
            ->assertSee('J Funding Capital Program')
            ->assertSee('Capital Funding Leads KPIs');

        $this->get(route('programs.show', 'southernmost-surety'))->assertForbidden();
        $this->get(route('programs.show', 'k-and-g-art-design'))->assertForbidden();
    }

    public function test_construction_program_can_include_remodeling_and_cnc_workspaces(): void
    {
        $this->actingAs(User::factory()->kgEmployee()->create());

        $this->get(route('programs.show', 'k-and-g-art-design'))
            ->assertOk()
            ->assertSee('K &amp; G Art Design Program', false)
            ->assertSee('Construction &amp; Remodeling KPIs', false)
            ->assertSee('CNC Quote Intake KPIs')
            ->assertSee('CNC cutting services')
            ->assertSee('Create Project')
            ->assertSee('Create Lead');
    }

    public function test_removed_company_programs_are_not_registered(): void
    {
        foreach (['research-management-partners', 'mru-research', 'cnc-cutting-services'] as $program) {
            $this->get(route('programs.show', $program))->assertNotFound();
        }
    }

    public function test_capital_funding_lead_can_be_created(): void
    {
        $response = $this->post('/capital-funding/leads', [
            'title' => 'Northline HVAC',
            'company_name' => 'Northline HVAC',
            'first_name' => 'Dana',
            'last_name' => 'Rivera',
            'phone' => '555-1000',
            'email' => 'dana@example.com',
            'estimated_value' => 85000,
            'priority' => 'high',
            'profile' => [
                'monthly_revenue' => 120000,
                'requested_amount' => 85000,
                'funding_purpose' => 'Working capital',
                'time_in_business' => '6 years',
                'credit_score_range' => '640-699',
                'bank_statements_uploaded' => '1',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['title' => 'Northline HVAC', 'status' => 'new_request']);
        $this->assertDatabaseHas('funding_profiles', ['funding_purpose' => 'Working capital']);
    }

    public function test_bond_agency_lead_can_be_created(): void
    {
        $response = $this->post('/bond-agency/leads', [
            'title' => 'Summit Bond Group',
            'company_name' => 'Summit Bond Group',
            'first_name' => 'Nia',
            'last_name' => 'Harris',
            'phone' => '555-2000',
            'email' => 'nia@example.com',
            'estimated_value' => 12000,
            'priority' => 'normal',
            'profile' => [
                'agency_type' => 'Bail bond agency',
                'county' => 'Orange',
                'current_products' => 'Commercial bond add-ons',
                'coverage_gap' => 'Renewal process is not tracked',
                'renewal_date' => '2026-08-01',
                'quote_amount' => 12000,
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['title' => 'Summit Bond Group', 'status' => 'new_agency']);
        $this->assertDatabaseHas('bond_profiles', ['coverage_gap' => 'Renewal process is not tracked']);
    }

    public function test_bond_agency_import_page_renders(): void
    {
        $this->get('/bond-agency/import')
            ->assertOk()
            ->assertSee('Import Bail Bond Agencies');
    }

    public function test_bond_agency_import_accepts_synergia_csv(): void
    {
        $csv = implode("\n", [
            'agency_name,contact_name,email,phone,address,city,state,zip,county,website,current_surety,coverage_gap',
            'Summit Bail Bonds,Nia Harris,nia@example.com,555-2000,123 Main St,Phoenix,AZ,85003,Maricopa,https://example.com,Current Surety,Needs ePower support',
        ]);

        $response = $this->post('/bond-agency/import', [
            'csv_file' => UploadedFile::fake()->createWithContent('agencies.csv', $csv),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('companies', ['name' => 'Summit Bail Bonds', 'city' => 'Phoenix']);
        $this->assertDatabaseHas('contacts', ['email' => 'nia@example.com']);
        $this->assertDatabaseHas('leads', ['title' => 'Summit Bail Bonds - Palmetto Opportunity']);
        $this->assertDatabaseHas('bond_profiles', ['coverage_gap' => 'Needs ePower support']);
    }

    public function test_bond_agency_import_accepts_difi_sbs_csv(): void
    {
        $csv = implode("\n", [
            'LICENSENO,JURISDICTION_NAME,NPN,FIRST_NAME,MIDDLE_NAME,LAST_NAME,SUFFIX,BUSINESS_ENTITY_NAME,LICENSE_STATUS,LICENSE_CLASS_DESC,LOA,DOMICILESTATE,IS_RESIDENT,FIRST_ACTIVE_DATE,EFFECTIVE_DATE,EXPIRATION_DATE,BUS_ADDRESS1,BUS_ADDRESS2,BUS_ADDRESS3,BUS_ADDRESS4,BUS_ADDRESS5,BUS_ADDRESS6,BUS_CITY,BUS_STATE,BUS_ZIP,BUS_COUNTRY,MLG_ADDRESS1,MLG_ADDRESS2,MLG_ADDRESS3,MLG_CITY,MLG_STATE,MLG_ZIP,MLG_COUNTRY,PHONE1,EMAIL1,CE_COMPLIANCE',
            'AZ-12345,Arizona,9876543,Dana,,Rivera,,Desert Bond Agency,Active,Bail Bond Agent,Bail Bond Agent,Arizona,Y,03/01/2023,03/01/2023,06/30/2028,400 Court Ave,Suite 10,,,,,Tucson,Arizona,85701,United States,PO BOX 1,,,Tucson,Arizona,85702,United States,555-3000,dana@example.com,',
        ]);

        $response = $this->post('/bond-agency/import', [
            'csv_file' => UploadedFile::fake()->createWithContent('difi-sbs.csv', $csv),
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('companies', ['name' => 'Desert Bond Agency', 'city' => 'Tucson', 'state' => 'AZ']);
        $this->assertDatabaseHas('contacts', ['email' => 'dana@example.com']);

        $lead = Lead::where('title', 'Desert Bond Agency - Palmetto Opportunity')->firstOrFail();

        $this->assertSame('difi_sbs_import', $lead->metadata['lead_source']);
        $this->assertSame('AZ-12345', $lead->metadata['license_number']);
        $this->assertSame('Bail Bond Agent', $lead->metadata['license_line']);
    }

    public function test_bond_agency_outreach_queue_renders(): void
    {
        AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_name' => 'Dana Rivera',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'draft',
        ]);

        $this->get('/bond-agency/outreach')
            ->assertOk()
            ->assertSee('Outreach Queue')
            ->assertSee('Email Production')
            ->assertSee('Ready To Send')
            ->assertSee('Desert Bond Agency')
            ->assertSee('dana@example.com');
    }

    public function test_bond_agency_outreach_draft_can_be_edited_before_production_send(): void
    {
        $message = AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_name' => 'Dana',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->post(route('bond-agency.outreach.update', $message), [
            'recipient_name' => 'Dana Rivera',
            'recipient_email' => 'dana.rivera@example.com',
            'subject' => 'Palmetto surety coverage review',
            'message_body' => 'Could we schedule a quick Palmetto coverage review this week?',
        ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Outreach draft updated. Approve it again before sending.');

        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $message->id,
            'recipient_name' => 'Dana Rivera',
            'recipient_email' => 'dana.rivera@example.com',
            'subject' => 'Palmetto surety coverage review',
            'message_body' => 'Could we schedule a quick Palmetto coverage review this week?',
            'status' => 'draft',
            'approved_at' => null,
        ]);
    }

    public function test_bond_agency_signature_page_renders(): void
    {
        $this->get('/bond-agency/outreach/signature')
            ->assertOk()
            ->assertSee('SynNexus')
            ->assertSee('In partnership with')
            ->assertSee('Southernmost Surety')
            ->assertSee('Palmetto Surety')
            ->assertSee('Global Synergia Group')
            ->assertSee('globalsynergiagroup.com')
            ->assertSee('images/outreach/synnexus-logo.png')
            ->assertSee('images/outreach/global-synergia-group-logo.png')
            ->assertSee('images/outreach/southernmost-surety-logo.svg')
            ->assertSee('images/outreach/palmetto-surety-logo.gif');
    }

    public function test_bond_agency_outreach_status_can_be_updated(): void
    {
        $message = AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'draft',
        ]);

        $this->post(route('bond-agency.outreach.approve', $message))->assertRedirect();
        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $message->id,
            'status' => 'approved',
        ]);

        $this->post(route('bond-agency.outreach.sent', $message))->assertRedirect();
        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $message->id,
            'status' => 'sent',
        ]);
    }

    public function test_approved_bond_agency_outreach_can_be_sent_through_microsoft_graph(): void
    {
        config([
            'services.microsoft.sender_email' => 'jess@example.com',
            'services.microsoft.sender_phone' => '305-490-5407',
            'services.microsoft.sender_website' => 'globalsynergiagroup.com',
        ]);

        $this->actingAs(User::factory()->platformAdmin()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'access-token',
            'microsoft_token_expires_at' => now()->addHour(),
        ]));

        $lead = $this->createCommandQueueLead();
        $message = AiOutreachMessage::create([
            'lead_id' => $lead->id,
            'agency_name' => 'Desert Bond Agency',
            'recipient_name' => 'Dana Rivera',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Http::fake([
            'https://graph.microsoft.com/v1.0/me/sendMail' => Http::response('', 202),
        ]);

        $this->post(route('bond-agency.outreach.send', $message))
            ->assertRedirect()
            ->assertSessionHas('success', 'Outreach email sent.');

        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $message->id,
            'status' => 'sent',
        ]);
        $this->assertNotNull($message->fresh()->sent_at);
        $this->assertSame('Monitor reply or follow up in three business days', $lead->fresh()->next_best_action);
        $this->assertDatabaseHas('outreach_events', [
            'ai_outreach_message_id' => $message->id,
            'event_type' => 'sent',
            'provider' => 'microsoft_graph',
        ]);

        Http::assertSent(function (HttpRequest $request) {
            $payload = $request->data();

            return $request->url() === 'https://graph.microsoft.com/v1.0/me/sendMail'
                && $request->hasHeader('Authorization', 'Bearer access-token')
                && $payload['message']['subject'] === 'Additional coverage and electronic powers for your agency'
                && $payload['message']['toRecipients'][0]['emailAddress']['address'] === 'dana@example.com'
                && $payload['message']['body']['contentType'] === 'HTML'
                && str_contains($payload['message']['body']['content'], 'Would you be open to a quick conversation?')
                && str_contains($payload['message']['body']['content'], 'SynNexus');
        });
    }

    public function test_send_approved_only_sends_production_ready_outreach(): void
    {
        config([
            'services.microsoft.sender_email' => 'jess@example.com',
        ]);

        $this->actingAs(User::factory()->platformAdmin()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'access-token',
            'microsoft_token_expires_at' => now()->addHour(),
        ]));

        $ready = AiOutreachMessage::create([
            'agency_name' => 'Ready Bail Bonds',
            'recipient_email' => 'ready@example.com',
            'subject' => 'Palmetto surety review',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now()->subMinutes(2),
        ]);

        $missingEmail = AiOutreachMessage::create([
            'agency_name' => 'Missing Email Bail Bonds',
            'recipient_email' => null,
            'subject' => 'Palmetto surety review',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now()->subMinute(),
        ]);

        $invalidEmail = AiOutreachMessage::create([
            'agency_name' => 'Invalid Email Bail Bonds',
            'recipient_email' => 'not-an-email',
            'subject' => 'Palmetto surety review',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Http::fake([
            'https://graph.microsoft.com/v1.0/me/sendMail' => Http::response('', 202),
        ]);

        $this->post(route('bond-agency.outreach.send-approved'), ['limit' => 10])
            ->assertRedirect()
            ->assertSessionHas('success', 'Sent 1 approved outreach email(s).');

        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $ready->id,
            'status' => 'sent',
        ]);
        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $missingEmail->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $invalidEmail->id,
            'status' => 'approved',
        ]);

        Http::assertSentCount(1);
        Http::assertSent(function (HttpRequest $request) {
            return $request->url() === 'https://graph.microsoft.com/v1.0/me/sendMail'
                && $request->data()['message']['toRecipients'][0]['emailAddress']['address'] === 'ready@example.com';
        });
    }

    public function test_send_approved_requires_ready_outreach(): void
    {
        $this->actingAs(User::factory()->platformAdmin()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'access-token',
            'microsoft_token_expires_at' => now()->addHour(),
        ]));

        AiOutreachMessage::create([
            'agency_name' => 'Missing Email Bail Bonds',
            'recipient_email' => null,
            'subject' => 'Palmetto surety review',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Http::fake();

        $this->post(route('bond-agency.outreach.send-approved'), ['limit' => 10])
            ->assertRedirect()
            ->assertSessionHas('error', 'No approved outreach emails are ready to send.');

        Http::assertNothingSent();
    }

    public function test_outreach_draft_must_be_approved_before_microsoft_send(): void
    {
        $this->actingAs(User::factory()->platformAdmin()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'access-token',
            'microsoft_token_expires_at' => now()->addHour(),
        ]));

        $message = AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'draft',
        ]);

        Http::fake();

        $this->post(route('bond-agency.outreach.send', $message))
            ->assertRedirect()
            ->assertSessionHas('error', 'Approve this outreach draft before sending it.');

        $this->assertDatabaseHas('ai_outreach_messages', [
            'id' => $message->id,
            'status' => 'draft',
        ]);
        Http::assertNothingSent();
    }

    public function test_expired_microsoft_access_token_is_refreshed_before_sending_outreach(): void
    {
        config([
            'services.microsoft.client_id' => 'client-id',
            'services.microsoft.client_secret' => 'client-secret',
            'services.microsoft.tenant' => 'test-tenant',
            'services.microsoft.scopes' => ['openid', 'profile', 'email', 'offline_access', 'User.Read', 'Mail.Send'],
        ]);

        $user = User::factory()->platformAdmin()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'old-token',
            'microsoft_refresh_token' => 'refresh-token',
            'microsoft_token_expires_at' => now()->subMinute(),
        ]);

        $this->actingAs($user);

        $message = AiOutreachMessage::create([
            'agency_name' => 'Desert Bond Agency',
            'recipient_email' => 'dana@example.com',
            'subject' => 'Additional coverage and electronic powers for your agency',
            'message_body' => 'Would you be open to a quick conversation?',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Http::fake([
            'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/token' => Http::response([
                'access_token' => 'new-token',
                'refresh_token' => 'new-refresh-token',
                'expires_in' => 3600,
            ]),
            'https://graph.microsoft.com/v1.0/me/sendMail' => Http::response('', 202),
        ]);

        $this->post(route('bond-agency.outreach.send', $message))
            ->assertRedirect()
            ->assertSessionHas('success', 'Outreach email sent.');

        $user->refresh();

        $this->assertSame('new-token', $user->microsoft_access_token);
        $this->assertSame('new-refresh-token', $user->microsoft_refresh_token);

        Http::assertSent(function (HttpRequest $request) {
            return $request->url() === 'https://graph.microsoft.com/v1.0/me/sendMail'
                && $request->hasHeader('Authorization', 'Bearer new-token');
        });
    }

    public function test_command_queue_renders_browser_view(): void
    {
        $lead = $this->createCommandQueueLead();

        $this->get('/command-queue')
            ->assertOk()
            ->assertSee('Command Queue')
            ->assertSee($lead->title)
            ->assertSee('Identify decision maker');
    }

    public function test_command_queue_can_return_json_feed(): void
    {
        $lead = $this->createCommandQueueLead();

        $this->getJson('/command-queue?format=json')
            ->assertOk()
            ->assertJsonPath('data.0.title', $lead->title);
    }

    public function test_clinical_recruitment_lead_can_be_created(): void
    {
        $response = $this->post('/clinical-recruitment/leads', [
            'title' => 'Avery Stone',
            'first_name' => 'Avery',
            'last_name' => 'Stone',
            'phone' => '555-3000',
            'email' => 'avery@example.com',
            'priority' => 'urgent',
            'profile' => [
                'dob' => '1985-04-12',
                'condition_interest' => 'Cardiology',
                'protocol_interest' => 'CARD-220',
                'prescreen_status' => 'Not started',
                'prescreen_notes' => 'Candidate requested morning call window.',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['title' => 'Avery Stone', 'status' => 'new_candidate']);
        $this->assertDatabaseHas('clinical_profiles', ['condition_interest' => 'Cardiology']);
    }

    public function test_cnc_quote_lead_can_be_created(): void
    {
        $response = $this->post('/cnc-quote/leads', [
            'title' => 'Valve Block Batch',
            'company_name' => 'Precision Dynamics',
            'first_name' => 'Morgan',
            'last_name' => 'Lee',
            'phone' => '555-4555',
            'email' => 'morgan@example.com',
            'estimated_value' => 9600,
            'priority' => 'high',
            'profile' => [
                'part_name' => 'Valve Block V2',
                'material' => '6061 Aluminum',
                'quantity' => 120,
                'process_type' => 'CNC Milling',
                'tolerance_notes' => '+/- 0.002 on critical bores',
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['title' => 'Valve Block Batch', 'status' => 'new_quote_request']);
        $this->assertDatabaseHas('cnc_quote_profiles', ['part_name' => 'Valve Block V2']);
    }

    public function test_public_synnexus_intake_creates_cnc_lead(): void
    {
        $this->get('/intake/cnc-quote')
            ->assertOk()
            ->assertSee('K & G Art Designs CNC Quote Intake');

        $response = $this->post('/intake/cnc-quote', [
            'title' => 'Titanium Spacer RFQ',
            'company_name' => 'Apex Motion Labs',
            'first_name' => 'Sam',
            'last_name' => 'Nguyen',
            'email' => 'sam@example.com',
            'priority' => 'urgent',
            'profile' => [
                'part_name' => 'Spacer Ring',
                'material' => 'Ti-6Al-4V',
                'quantity' => 40,
                'process_type' => 'CNC Turning',
            ],
        ]);

        $response->assertRedirect('/intake/cnc-quote');
        $this->assertDatabaseHas('leads', [
            'title' => 'Titanium Spacer RFQ',
            'status' => 'new_quote_request',
            'priority' => 'urgent',
        ]);
        $this->assertDatabaseHas('cnc_quote_profiles', [
            'part_name' => 'Spacer Ring',
            'material' => 'Ti-6Al-4V',
        ]);
    }

    public function test_kg_employee_has_access_to_construction_and_cnc_modules_only(): void
    {
        $this->actingAs(User::factory()->kgEmployee()->create());

        $this->get('/remodeling')->assertOk();
        $this->get('/cnc-quote')->assertOk();
        $this->get('/bond-agency')->assertForbidden();
        $this->get('/capital-funding')->assertForbidden();
        $this->get('/clinical-recruitment')->assertForbidden();
        $this->get('/clinical-regulatory')->assertForbidden();
        $this->get('/command-queue')->assertForbidden();
    }

    public function test_funding_client_has_access_to_funding_module_only(): void
    {
        $this->actingAs(User::factory()->fundingClientUser()->create());

        $this->get('/capital-funding')->assertOk();
        $this->get('/remodeling')->assertForbidden();
        $this->get('/bond-agency')->assertForbidden();
        $this->get('/clinical-recruitment')->assertForbidden();
        $this->get('/clinical-regulatory')->assertForbidden();
        $this->get('/cnc-quote')->assertForbidden();
    }

    public function test_bail_bond_client_has_access_to_bond_module_only(): void
    {
        $this->actingAs(User::factory()->bondClientUser()->create());

        $this->get('/bond-agency')->assertOk();
        $this->get('/remodeling')->assertForbidden();
        $this->get('/capital-funding')->assertForbidden();
        $this->get('/clinical-recruitment')->assertForbidden();
        $this->get('/clinical-regulatory')->assertForbidden();
        $this->get('/cnc-quote')->assertForbidden();
    }

    public function test_research_client_has_access_to_research_module_only(): void
    {
        $this->actingAs(User::factory()->researchClientUser()->create());

        $this->get('/clinical-recruitment')->assertOk();
        $this->get('/clinical-regulatory')->assertOk();
        $this->get('/remodeling')->assertForbidden();
        $this->get('/capital-funding')->assertForbidden();
        $this->get('/bond-agency')->assertForbidden();
        $this->get('/cnc-quote')->assertForbidden();
    }

    public function test_platform_admin_can_open_user_access_management_page(): void
    {
        $this->get('/admin/user-access')
            ->assertOk()
            ->assertSee('User Access Management')
            ->assertSee('Construction')
            ->assertSee('remodeling');
    }

    public function test_module_user_can_open_dashboard_but_route_access_stays_scoped(): void
    {
        $this->actingAs(User::factory()->fundingClientUser()->create());

        $this->get('/dashboard')
            ->assertRedirect(route('programs.show', 'j-funding-capital'));

        $this->get('/capital-funding')->assertOk();
        $this->get('/bond-agency')->assertForbidden();
        $this->get('/remodeling')->assertForbidden();
    }

    public function test_medisana_user_is_limited_to_the_medisana_workspace(): void
    {
        $this->actingAs(User::factory()->medisanaUser()->create());

        $this->get('/dashboard')
            ->assertRedirect(route('programs.show', 'medisana-research-center'));

        $this->get(route('programs.show', 'medisana-research-center'))
            ->assertOk()
            ->assertSee('Medisana Research Center Program');

        $this->get(route('programs.show', 'ara-professionals'))->assertForbidden();
        $this->get(route('programs.show', 'synnexus'))->assertForbidden();
    }

    public function test_medisana_user_cannot_see_another_research_tenants_records(): void
    {
        $industry = Industry::create([
            'name' => 'Clinical Research',
            'slug' => 'clinical-research',
            'is_active' => true,
        ]);

        Lead::create([
            'industry_id' => $industry->id,
            'organization_key' => 'medisana-research-center',
            'title' => 'Medisana Candidate',
            'status' => 'new_candidate',
            'stage' => 'intake',
            'priority' => 'normal',
        ]);

        Lead::create([
            'industry_id' => $industry->id,
            'organization_key' => 'ara-professionals',
            'title' => 'ARA Candidate',
            'status' => 'new_candidate',
            'stage' => 'intake',
            'priority' => 'normal',
        ]);

        $medisanaRecord = RegulatoryServiceRecord::create([
            'organization_key' => 'medisana-research-center',
            'client_name' => 'Medisana Research Center',
            'service_category' => 'credentialing',
            'request_title' => 'Medisana Credential Packet',
            'status' => 'not_started',
            'priority' => 'normal',
        ]);

        $araRecord = RegulatoryServiceRecord::create([
            'organization_key' => 'ara-professionals',
            'client_name' => 'ARA Professionals',
            'service_category' => 'credentialing',
            'request_title' => 'ARA Credential Packet',
            'status' => 'not_started',
            'priority' => 'normal',
        ]);

        $this->actingAs(User::factory()->medisanaUser()->create());

        $this->get(route('clinical-recruitment.leads.index'))
            ->assertOk()
            ->assertSee('Medisana Candidate')
            ->assertDontSee('ARA Candidate');

        $this->get(route('clinical-regulatory.records.index'))
            ->assertOk()
            ->assertSee('Medisana Credential Packet')
            ->assertDontSee('ARA Credential Packet');

        $this->get(route('clinical-regulatory.records.show', $medisanaRecord))->assertOk();
        $this->get(route('clinical-regulatory.records.show', $araRecord))->assertForbidden();
    }

    public function test_non_admin_user_cannot_open_user_access_management_page(): void
    {
        $this->actingAs(User::factory()->kgEmployee()->create());

        $this->get('/admin/user-access')->assertForbidden();
    }

    private function createCommandQueueLead(): Lead
    {
        $industry = Industry::create([
            'name' => 'Bond Agency Insurance',
            'slug' => 'bond-agency-insurance',
            'is_active' => true,
        ]);

        $contact = Contact::create([
            'first_name' => 'Dana',
            'last_name' => 'Rivera',
            'phone' => '555-3000',
            'email' => 'dana@example.com',
        ]);

        return Lead::create([
            'industry_id' => $industry->id,
            'contact_id' => $contact->id,
            'title' => 'Desert Bond Agency',
            'status' => 'new_agency',
            'stage' => 'intake',
            'priority' => 'high',
            'estimated_value' => 12000,
            'lead_score' => 70,
            'next_follow_up_at' => now()->subHour(),
            'sla_status' => 'warning',
            'next_best_action' => 'Identify decision maker',
        ]);
    }
}
