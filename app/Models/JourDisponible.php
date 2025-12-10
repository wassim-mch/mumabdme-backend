<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourDisponible extends Model
{
    protected $fillable = ['service_id', 'day'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
