<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionFrequencyUnit extends Model
{
    protected $guarded = ['id'];

    public function licenses()
    {
        return $this->hasMany(License::class, 'action_frequency_unit_id');
    }
}
