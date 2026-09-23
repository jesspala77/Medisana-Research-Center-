<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulatoryServiceRecord extends Model
{
    protected $fillable = [
        'organization_key',
        'client_name',
        'site_name',
        'service_category',
        'request_title',
        'sponsor',
        'protocol',
        'primary_contact_name',
        'primary_contact_email',
        'primary_contact_phone',
        'regulatory_owner',
        'status',
        'priority',
        'due_date',
        'current_blocker',
        'documents_available',
        'missing_documents',
        'approval_path',
        'next_step',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }
}
