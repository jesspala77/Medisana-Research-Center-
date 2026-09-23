<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionImportBatch extends Model
{
    protected $fillable = [
        'source', 'filename', 'status', 'rows_total', 'rows_imported',
        'rows_skipped', 'rows_failed', 'mapping', 'errors', 'imported_by',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'mapping' => 'array',
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
}
