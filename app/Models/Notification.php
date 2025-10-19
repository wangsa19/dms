<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'document_id',
        'license_id',
        'user_id',
        'message',
        'status'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
