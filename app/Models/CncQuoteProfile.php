<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CncQuoteProfile extends Model
{
    protected $fillable = [
        'lead_id',
        'part_name',
        'material',
        'quantity',
        'process_type',
        'tolerance_notes',
        'surface_finish',
        'target_unit_price',
        'due_date',
        'cad_file_url',
        'shipping_postal_code',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'target_unit_price' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}