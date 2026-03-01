<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];

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
