<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
