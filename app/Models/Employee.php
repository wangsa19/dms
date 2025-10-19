<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nik',
        'name',
        'gender',
        'phone',
        'position_id',
        'section_id',
        'department_id'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'owner_id');
    }

    public function licenses()
    {
        return $this->hasMany(License::class, 'owner_id');
    }
}
