<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'code'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
