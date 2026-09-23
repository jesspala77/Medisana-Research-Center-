<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\AiOutreachMessage;

class AiOutreachDraftService
{
    public function createPalmettoDraft(Lead $lead): AiOutreachMessage
    {
        $lead->loadMissing(['company', 'contact', 'bondProfile']);

        $agencyName = $lead->company->name ?? 'your agency';
        $recipientName = $lead->contact->first_name ?? null;
        $recipientEmail = $lead->contact->email ?? null;

        $subject = 'Additional coverage and electronic powers for your agency';

        $greeting = $recipientName ? "Hi {$recipientName}," : "Hello,";

        $body = "{$greeting}\n\n"
            . "I wanted to reach out because we are helping bail bond agencies improve profitability and streamline operations through Southernmost Surety and Palmetto.\n\n"
            . "Palmetto may be able to provide additional coverage at a lower rate, along with electronic powers to make bond issuance faster, cleaner, and more efficient.\n\n"
            . "This could help your agency:\n"
            . "- Increase available coverage\n"
            . "- Reduce administrative delays\n"
            . "- Improve productivity with electronic powers\n"
            . "- Keep more money in your pocket\n\n"
            . "Would you be open to a quick conversation to see whether Palmetto could be a good fit for your agency?\n\n"
            . "Best,\n"
            . "Jessica Palacio";

        return AiOutreachMessage::create([
            'lead_id' => $lead->id,
            'agency_name' => $agencyName,
            'recipient_name' => $recipientName,
            'recipient_email' => $recipientEmail,
            'subject' => $subject,
            'message_body' => $body,
            'status' => 'draft',
            'metadata' => [
                'campaign_type' => 'palmetto_intro',
                'source' => 'synnexus_ai_draft_service',
            ],
        ]);
    }
}