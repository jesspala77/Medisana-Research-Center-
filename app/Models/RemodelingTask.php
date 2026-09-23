<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemodelingTask extends Model
{
    protected $fillable = ['remodeling_project_id', 'assigned_to', 'title', 'description', 'status', 'priority', 'due_date', 'completed_at'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }
}
