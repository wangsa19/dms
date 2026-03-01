<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = ['id'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
