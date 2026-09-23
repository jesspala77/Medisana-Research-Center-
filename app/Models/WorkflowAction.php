<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowAction extends Model
{
    protected $fillable = [
        'industry_id', 'name', 'slug', 'button_label', 'new_status', 'new_stage',
        'follow_up_hours', 'activity_type', 'activity_note', 'task_template', 'is_active',
    ];

    protected $casts = ['task_template' => 'array'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
