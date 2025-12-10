<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['service_id', 'path'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
