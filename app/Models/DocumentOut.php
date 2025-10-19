<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentOut extends Model
{
    protected $fillable = [
        'document_id',
        'borrower_id',
        'checkout_time',
        'return_time',
        'status'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function borrower()
    {
        return $this->belongsTo(Employee::class, 'borrower_id');
    }
}
