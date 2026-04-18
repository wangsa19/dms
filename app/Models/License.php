<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $guarded = ['id'];

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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function documentType()
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

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function actionFrequencyUnit()
    {
        return $this->belongsTo(ActionFrequencyUnit::class, 'action_frequency_unit_id');
    }
}
