<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RdvItem extends Model
{
    protected $fillable = ['rdv_id', 'service_id', 'price'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
