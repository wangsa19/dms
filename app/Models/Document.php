<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'document_type_id',
        'category_id',
        'field_id',
        'department_id',
        'section_id',
        'owner_id',
        'location',
        'call_number',
        'status'
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function owner()
    {
        return $this->belongsTo(Employee::class, 'owner_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function outs()
    {
        return $this->hasMany(DocumentOut::class);
    }
}
