<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apiary extends Model
{
    protected $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
        'image_url',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function hives()
    {
        return $this->hasMany(Hive::class);
    }
}
