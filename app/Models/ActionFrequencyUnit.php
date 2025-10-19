<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionFrequencyUnit extends Model
{
    protected $fillable = ['name', 'code'];

    public function licenses()
    {
        return $this->hasMany(License::class, 'action_frequency_unit_id');
    }
}
