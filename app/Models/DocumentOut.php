<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentOut extends Model
{
    protected $guarded = ['id'];
    
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function borrower()
    {
        return $this->belongsTo(Employee::class, 'borrower_id');
    }
}
