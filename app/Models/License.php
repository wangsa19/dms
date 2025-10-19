<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'name_en',
        'name_id',
        'name_jp',
        'document_type_id',
        'occurrence_type',
        'category_id',
        'field_id',
        'department_id',
        'owner_id',
        'status',
        'start_date',
        'end_date',
        'reminder_date',
        'government_issuer',
        'action_frequency_value',
        'action_frequency_unit_id'
    ];

    public function versions()
    {
        return $this->hasMany(LicenseVersion::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function owner()
    {
        return $this->belongsTo(Employee::class, 'owner_id');
    }

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

    public function unit()
    {
        return $this->belongsTo(ActionFrequencyUnit::class, 'action_frequency_unit_id');
    }
}
