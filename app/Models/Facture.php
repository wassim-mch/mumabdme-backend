<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = ['user_id', 'rdv_id', 'total'];

    public function rdv()
    {
        return $this->belongsTo(Rdv::class);
    }
}
