<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseVersion extends Model
{
    protected $guarded = ['id'];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
