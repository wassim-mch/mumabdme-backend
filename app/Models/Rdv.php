<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    protected $fillable = ['user_id', 'status', 'scheduled_at'];

    public function items()
    {
        return $this->hasMany(RdvItem::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
