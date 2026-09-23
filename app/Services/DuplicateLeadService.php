<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class DuplicateLeadService
{
    public function scan(Lead $lead): void
    {
        $contact = $lead->contact;
        if (! $contact) {
            return;
        }

        $matches = Lead::query()
            ->where('id', '!=', $lead->id)
            ->whereHas('contact', function ($q) use ($contact) {
                $q->when($contact->email, fn ($x) => $x->orWhere('email', $contact->email))
                    ->when($contact->phone, fn ($x) => $x->orWhere('phone', $contact->phone));
            })
            ->limit(10)
            ->get();

        foreach ($matches as $match) {
            $reasons = [];
            $score = 0;

            if ($contact->email && $contact->email === optional($match->contact)->email) {
                $score += 60;
                $reasons[] = 'Same email';
            }

            if ($contact->phone && $contact->phone === optional($match->contact)->phone) {
                $score += 40;
                $reasons[] = 'Same phone';
            }

            DB::table('duplicate_candidates')->updateOrInsert(
                [
                    'lead_id' => $lead->id,
                    'possible_duplicate_lead_id' => $match->id,
                ],
                [
                    'confidence_score' => min(100, $score),
                    'matching_reasons' => json_encode($reasons),
                    'status' => 'pending',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
