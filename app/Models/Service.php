<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'duration',
        'price',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function joursDisponibles()
    {
        return $this->hasMany(JourDisponible::class);
    }
}
