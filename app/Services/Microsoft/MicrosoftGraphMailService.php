<?php

namespace App\Services\Microsoft;

use App\Models\AiOutreachMessage;
use App\Models\User;
use App\Services\MicrosoftGraphTokenService;
use Illuminate\Support\Facades\Http;

class MicrosoftGraphMailService
{
    public function __construct(private MicrosoftGraphTokenService $tokens) {}

    public function sendOutreachMessage(AiOutreachMessage $message, User $sender): array
    {
        $endpoint = 'https://graph.microsoft.com/v1.0/me/sendMail';
        $graphMessage = [
            'subject' => $message->subject,
            'body' => [
                'contentType' => 'HTML',
                'content' => $this->htmlBody($message),
            ],
            'toRecipients' => [
                $this->recipient($message->recipient_email, $message->recipient_name),
            ],
            'internetMessageHeaders' => $this->messageHeaders($message),
        ];

        $replyTo = $this->replyToRecipients();

        if ($replyTo) {
            $graphMessage['replyTo'] = $replyTo;
        }

        $response = Http::withToken($this->tokens->accessToken(
            $sender,
            'Connect Microsoft before sending outreach emails.',
            'Reconnect Microsoft before sending outreach emails.'
        ))
            ->acceptJson()
            ->post($endpoint, [
                'message' => $graphMessage,
                'saveToSentItems' => true,
            ]);

        if (! $response->successful()) {
            $response->throw();
        }

        return [
            'provider' => 'microsoft_graph',
            'provider_message_id' => null,
            'endpoint' => $endpoint,
            'status' => $response->status(),
            'sender_email' => $sender->microsoft_email ?: config('services.microsoft.sender_email'),
        ];
    }

    private function htmlBody(AiOutreachMessage $message): string
    {
        $signatureHtml = view('outreach.partials.signature', [
            'phone' => config('services.microsoft.sender_phone'),
            'email' => config('services.microsoft.sender_email'),
            'website' => config('services.microsoft.sender_website'),
            'absoluteUrls' => true,
        ])->render();

        return '<div style="font-family: Arial, sans-serif; color:#111827; font-size:14px; line-height:1.6;">'
            .nl2br(e((string) $message->message_body))
            .'</div>'
            .'<div style="margin-top:20px;">'.$signatureHtml.'</div>';
    }

    private function replyToRecipients(): array
    {
        $senderEmail = config('services.microsoft.sender_email');

        if (! filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
            return [];
        }

        return [
            $this->recipient($senderEmail),
        ];
    }

    private function recipient(string $email, ?string $name = null): array
    {
        $recipient = [
            'emailAddress' => [
                'address' => $email,
            ],
        ];

        if ($name) {
            $recipient['emailAddress']['name'] = $name;
        }

        return $recipient;
    }

    private function messageHeaders(AiOutreachMessage $message): array
    {
        $headers = [
            [
                'name' => 'x-synnexus-outreach-id',
                'value' => (string) $message->id,
            ],
        ];

        if ($message->lead_id) {
            $headers[] = [
                'name' => 'x-synnexus-lead-id',
                'value' => (string) $message->lead_id,
            ];
        }

        return $headers;
    }
}
