<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemodelingNote extends Model
{
    protected $fillable = ['remodeling_project_id', 'note_type', 'note'];

    public function project()
    {
        return $this->belongsTo(RemodelingProject::class, 'remodeling_project_id');
    }
}
