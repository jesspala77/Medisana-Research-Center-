<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadTask extends Model
{
    protected $fillable = [
        'lead_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'task_type',
        'due_at',
        'completed_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public static function createPalmettoFollowUp(Lead $lead, int $days = 3): self
    {
        return self::create([
            'lead_id' => $lead->id,
            'title' => 'Follow up on Palmetto opportunity',
            'description' => 'Contact agency regarding Palmetto coverage, lower rate, additional capacity, and electronic powers.',
            'status' => 'pending',
            'priority' => 'high',
            'task_type' => 'palmetto_follow_up',
            'due_at' => now()->addDays($days),
        ]);
    }

    public static function createEPowerOnboardingTask(Lead $lead, int $days = 1): self
    {
        return self::create([
            'lead_id' => $lead->id,
            'title' => 'Start ePower onboarding',
            'description' => 'Confirm electronic powers setup, training needs, and activation timeline.',
            'status' => 'pending',
            'priority' => 'high',
            'task_type' => 'epower_onboarding',
            'due_at' => now()->addDays($days),
        ]);
    }

    public static function createApplicationCheckTask(Lead $lead, int $days = 2): self
    {
        return self::create([
            'lead_id' => $lead->id,
            'title' => 'Check Palmetto application status',
            'description' => 'Review application status with Southernmost Surety and confirm next steps.',
            'status' => 'pending',
            'priority' => 'medium',
            'task_type' => 'palmetto_application_check',
            'due_at' => now()->addDays($days),
        ]);
    }
}