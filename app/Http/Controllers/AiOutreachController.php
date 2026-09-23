<?php

namespace App\Http\Controllers;

use App\Models\AiOutreachMessage;
use App\Models\OutreachEvent;
use App\Services\Microsoft\MicrosoftGraphMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AiOutreachController extends Controller
{
    private const STATUSES = ['draft', 'approved', 'sent', 'replied', 'opted_out'];

    public function index(Request $request)
    {
        $status = $request->query('status', 'draft');
        $status = in_array($status, [...self::STATUSES, 'all', 'missing_email'], true) ? $status : 'draft';

        $messages = $this->baseQuery()
            ->when($status === 'missing_email', fn ($query) => $query->where(fn ($query) => $query
                ->whereNull('recipient_email')
                ->orWhere('recipient_email', '')
            ))
            ->when(in_array($status, self::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('outreach.index', [
            'messages' => $messages,
            'status' => $status,
            'summary' => $this->summary(),
            'microsoftConnected' => (bool) $request->user()?->microsoft_access_token,
            'senderEmail' => $request->user()?->microsoft_email ?: config('services.microsoft.sender_email'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status', 'draft');
        $status = in_array($status, [...self::STATUSES, 'all'], true) ? $status : 'draft';
        $filename = 'bond-agency-outreach-'.$status.'-'.now()->format('Ymd-His').'.csv';

        $messages = $this->baseQuery()
            ->when(in_array($status, self::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->whereNotNull('recipient_email')
            ->where('recipient_email', '<>', '')
            ->latest();

        return response()->streamDownload(function () use ($messages) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'lead_id',
                'agency_name',
                'recipient_name',
                'recipient_email',
                'subject',
                'message_body',
                'phone',
                'city',
                'state',
                'license_number',
                'npn',
                'status',
            ]);

            $messages->chunk(100, function ($chunk) use ($file) {
                foreach ($chunk as $message) {
                    $lead = $message->lead;

                    fputcsv($file, [
                        $lead?->id,
                        $message->agency_name,
                        $message->recipient_name,
                        $message->recipient_email,
                        $message->subject,
                        $message->message_body,
                        $lead?->contact?->phone,
                        $lead?->company?->city ?? $lead?->metadata['city'] ?? null,
                        $lead?->company?->state ?? $lead?->metadata['state'] ?? null,
                        $lead?->metadata['license_number'] ?? null,
                        $lead?->metadata['npn'] ?? null,
                        $message->status,
                    ]);
                }
            });

            fclose($file);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function signature()
    {
        $signatureHtml = view('outreach.partials.signature', [
            'phone' => config('services.microsoft.sender_phone'),
            'email' => config('services.microsoft.sender_email'),
            'website' => config('services.microsoft.sender_website'),
            'absoluteUrls' => true,
        ])->render();

        return view('outreach.signature', [
            'signatureHtml' => $signatureHtml,
        ]);
    }

    public function approve(AiOutreachMessage $aiOutreachMessage)
    {
        $aiOutreachMessage->approve();
        $this->recordEvent($aiOutreachMessage, 'approved', 'manual');

        return back()->with('success', 'Outreach draft approved.');
    }

    public function update(Request $request, AiOutreachMessage $aiOutreachMessage)
    {
        if (in_array($aiOutreachMessage->status, ['sent', 'replied', 'opted_out'], true)) {
            return back()->with('error', 'Completed outreach emails cannot be edited.');
        }

        $validated = $request->validate([
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message_body' => ['required', 'string'],
        ]);

        $wasApproved = $aiOutreachMessage->status === 'approved';
        $metadata = array_merge($aiOutreachMessage->metadata ?? [], [
            'last_edited_by_user_id' => $request->user()->id,
            'last_edited_at' => now()->toIso8601String(),
        ]);

        $aiOutreachMessage->update([
            ...$validated,
            'status' => $wasApproved ? 'draft' : $aiOutreachMessage->status,
            'approved_at' => $wasApproved ? null : $aiOutreachMessage->approved_at,
            'metadata' => $metadata,
        ]);

        $this->recordEvent($aiOutreachMessage, 'edited', 'manual');

        return back()->with(
            'success',
            $wasApproved
                ? 'Outreach draft updated. Approve it again before sending.'
                : 'Outreach draft updated.'
        );
    }

    public function send(
        AiOutreachMessage $aiOutreachMessage,
        MicrosoftGraphMailService $mailService,
        Request $request
    ) {
        try {
            $this->sendMessage($aiOutreachMessage, $mailService, $request);

            return back()->with('success', 'Outreach email sent.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', $exception->getMessage());
        }
    }

    public function sendApproved(MicrosoftGraphMailService $mailService, Request $request)
    {
        $limit = min(max((int) $request->input('limit', 10), 1), 25);
        $messages = $this->sendableApprovedMessages($limit);

        if ($messages->isEmpty()) {
            return back()->with('error', 'No approved outreach emails are ready to send.');
        }

        $sent = 0;

        try {
            foreach ($messages as $message) {
                $this->sendMessage($message, $mailService, $request);
                $sent++;
            }
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                "Sent {$sent} email(s), then stopped: ".$exception->getMessage()
            );
        }

        return back()->with('success', "Sent {$sent} approved outreach email(s).");
    }

    public function markSent(AiOutreachMessage $aiOutreachMessage)
    {
        $aiOutreachMessage->markSent();
        $this->recordEvent($aiOutreachMessage, 'sent', 'manual');

        return back()->with('success', 'Outreach marked sent.');
    }

    public function markReplied(AiOutreachMessage $aiOutreachMessage)
    {
        $aiOutreachMessage->markReplied();
        $this->recordEvent($aiOutreachMessage, 'replied', 'manual');

        return back()->with('success', 'Outreach marked replied.');
    }

    public function optOut(AiOutreachMessage $aiOutreachMessage)
    {
        $aiOutreachMessage->markOptedOut();
        $this->recordEvent($aiOutreachMessage, 'opted_out', 'manual');

        return back()->with('success', 'Outreach marked opted out.');
    }

    private function baseQuery()
    {
        return AiOutreachMessage::with(['lead.company', 'lead.contact']);
    }

    private function sendMessage(
        AiOutreachMessage $message,
        MicrosoftGraphMailService $mailService,
        Request $request
    ): void {
        $this->assertSendable($message);

        $result = $mailService->sendOutreachMessage($message, $request->user());
        $metadata = array_merge($message->metadata ?? [], [
            'sent_via' => $result['provider'],
            'sent_by_user_id' => $request->user()->id,
            'sender_email' => $result['sender_email'],
            'graph_response_status' => $result['status'],
        ]);

        $message->update([
            'status' => 'sent',
            'sent_at' => now(),
            'metadata' => $metadata,
        ]);

        $message->lead?->update([
            'last_contacted_at' => now(),
            'next_follow_up_at' => now()->addDays(3),
            'next_best_action' => 'Monitor reply or follow up in three business days',
        ]);

        $this->recordEvent($message, 'sent', $result['provider'], $result);
    }

    private function assertSendable(AiOutreachMessage $message): void
    {
        if ($message->status !== 'approved') {
            throw new RuntimeException('Approve this outreach draft before sending it.');
        }

        if (! filter_var($message->recipient_email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Add a valid recipient email before sending.');
        }

        if (! trim((string) $message->subject)) {
            throw new RuntimeException('Add a subject before sending.');
        }

        if (! trim((string) $message->message_body)) {
            throw new RuntimeException('Add a message body before sending.');
        }
    }

    private function sendableApprovedMessages(int $limit): Collection
    {
        return $this->baseQuery()
            ->where('status', 'approved')
            ->whereNotNull('recipient_email')
            ->where('recipient_email', '<>', '')
            ->whereNotNull('subject')
            ->where('subject', '<>', '')
            ->whereNotNull('message_body')
            ->where('message_body', '<>', '')
            ->oldest('approved_at')
            ->limit($limit * 3)
            ->get()
            ->filter(fn (AiOutreachMessage $message): bool => $this->isSendable($message))
            ->take($limit)
            ->values();
    }

    private function isSendable(AiOutreachMessage $message): bool
    {
        return $message->status === 'approved'
            && filter_var($message->recipient_email, FILTER_VALIDATE_EMAIL)
            && trim((string) $message->subject) !== ''
            && trim((string) $message->message_body) !== '';
    }

    private function recordEvent(
        AiOutreachMessage $message,
        string $eventType,
        string $provider,
        array $metadata = []
    ): void {
        if (! $message->lead_id) {
            return;
        }

        OutreachEvent::create([
            'lead_id' => $message->lead_id,
            'ai_outreach_message_id' => $message->id,
            'event_type' => $eventType,
            'provider' => $provider,
            'provider_message_id' => $metadata['provider_message_id'] ?? null,
            'metadata' => $metadata ? array_diff_key($metadata, ['provider_message_id' => true]) : null,
        ]);
    }

    private function summary(): array
    {
        $counts = AiOutreachMessage::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $approvedMessages = AiOutreachMessage::where('status', 'approved')->get([
            'status',
            'recipient_email',
            'subject',
            'message_body',
        ]);
        $readyToSend = $approvedMessages
            ->filter(fn (AiOutreachMessage $message): bool => $this->isSendable($message))
            ->count();

        return [
            'all' => AiOutreachMessage::count(),
            'draft' => $counts['draft'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'ready_to_send' => $readyToSend,
            'blocked_approved' => max(0, ($counts['approved'] ?? 0) - $readyToSend),
            'sent' => $counts['sent'] ?? 0,
            'sent_today' => AiOutreachMessage::where('status', 'sent')
                ->where('sent_at', '>=', now()->startOfDay())
                ->count(),
            'replied' => $counts['replied'] ?? 0,
            'opted_out' => $counts['opted_out'] ?? 0,
            'missing_email' => AiOutreachMessage::where(fn ($query) => $query
                ->whereNull('recipient_email')
                ->orWhere('recipient_email', '')
            )->count(),
        ];
    }
}
